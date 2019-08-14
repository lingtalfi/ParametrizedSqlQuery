<?php


namespace Ling\ParametrizedSqlQuery;


use Ling\Bat\ArrayTool;
use Ling\ParametrizedSqlQuery\Exception\ParametrizedSqlQueryException;
use Ling\SqlQuery\SqlQuery;

/**
 * The ParametrizedSqlQueryUtil class.
 *
 * See @page(conception notes) for more details.
 *
 *
 *
 */
class ParametrizedSqlQueryUtil
{

    /**
     * This property holds the regMarker for this instance.
     * It's the regex used to extract the sql markers out of an expression.
     * @var string
     */
    protected static $regMarker = '!:([a-zA-Z_]+)!';

    /**
     * Returns an SqlQuery instance parametrized using the given request declaration and params.
     * Or throws an exception if something wrong occurs.
     *
     * For more information about the request declaration structure, see @page(the conception notes).
     *
     *
     *
     *
     * @param array $requestDeclaration
     *
     * @param array $tags
     * Array of tag => parameters.
     *
     * @return SqlQuery
     * @throws ParametrizedSqlQueryException
     */
    public function getSqlQuery(array $requestDeclaration, array $tags = []): SqlQuery
    {
        $query = new SqlQuery();
        $query->setDefaultWhereValue("0");


        if (ArrayTool::arrayKeyExistAll(['table', 'base_fields'], $requestDeclaration)) {


            //--------------------------------------------
            // BASE
            //--------------------------------------------
            $fields = $requestDeclaration['base_fields'];
            if (false === is_array($fields)) {
                $fields = [$fields];
            }
            $query->setTable($requestDeclaration['table']);
            foreach ($fields as $field) {
                $query->addField($field);
            }
            if (array_key_exists("base_join", $requestDeclaration)) {
                $baseJoin = $requestDeclaration['base_join'];
                if (false === is_array($baseJoin)) {
                    $baseJoin = [$baseJoin];
                }
                foreach ($baseJoin as $join) {
                    $query->addJoin($join);
                }
            }
            if (array_key_exists("base_group_by", $requestDeclaration)) {
                $baseGroupBy = $requestDeclaration['base_group_by'];
                if (false === is_array($baseGroupBy)) {
                    $baseGroupBy = [$baseGroupBy];
                }
                foreach ($baseGroupBy as $groupBy) {
                    $query->addGroupBy($groupBy);
                }
            }

            if (array_key_exists("base_having", $requestDeclaration)) {
                $baseHaving = $requestDeclaration['base_having'];
                if (false === is_array($baseHaving)) {
                    $baseHaving = [$baseHaving];
                }
                foreach ($baseHaving as $having) {
                    $query->addHaving($having);
                }
            }
            if (array_key_exists("base_order", $requestDeclaration)) {
                $baseOrder = $requestDeclaration['base_order'];
                if (false === is_array($baseOrder)) {
                    $baseOrder = [$baseOrder];
                }
                foreach ($baseOrder as $orderBy) {
                    $p = explode(' ', $orderBy, 2);
                    $query->addOrderBy($p[0], $p[1]);
                }
            }


            //--------------------------------------------
            // TAG BASED
            //--------------------------------------------
            $fields = $requestDeclaration['fields'] ?? [];
            $joins = $requestDeclaration['joins'] ?? [];
            $where = $requestDeclaration['where'] ?? [];
            $groupBy = $requestDeclaration['group_by'] ?? [];
            $having = $requestDeclaration['having'] ?? [];
            $order = $requestDeclaration['order'] ?? [];
            $requestMarkers = [];


            foreach ($tags as $tag => $v) {

                //--------------------------------------------
                // WHERE
                //--------------------------------------------
                if (array_key_exists($tag, $where)) {
                    if (is_array($v)) {

                        $whereExpr = $where[$tag];
                        $whereMarkers = $this->findMarkers($whereExpr);
                        foreach ($whereMarkers as $whereMarker) {
                            if (false === array_key_exists($whereMarker, $v)) {
                                $this->error("The value of $tag doesn't contain the $whereMarker marker.");
                            } else {
                                $requestMarkers[$whereMarker] = $v[$whereMarker];
                            }
                        }
                        $query->addWhere($whereExpr);


                    } else {
                        $this->error("The where parameter for tag $tag must be an array.");
                    }
                }


                //--------------------------------------------
                // GROUP BY
                //--------------------------------------------
                if (array_key_exists($tag, $groupBy)) {
                    $query->addGroupBy($groupBy[$tag]);
                }


                //--------------------------------------------
                // ORDER
                //--------------------------------------------
                if (array_key_exists($tag, $order)) {
                    $p = explode(' ', $order[$tag], 2);
                    $query->addOrderBy($p[0], $p[1]);
                }
            }


            if ($requestMarkers) {
                $query->addMarkers($requestMarkers);
            }


            //--------------------------------------------
            // LIMIT
            //--------------------------------------------
            $limit = $requestDeclaration['limit'] ?? null;
            if ($limit) {
                $page = $limit['page'];
                $length = $limit['length'];


                if ('$page' === $page || '$page_length' === $length) {
                    if (array_key_exists("limit", $tags)) {
                        $tagsLimit = $tags['limit'];
                        if (is_array($tagsLimit)) {
                            if ('$page' === $page) {
                                if (array_key_exists("page", $tagsLimit)) {
                                    $page = $tagsLimit['page'];
                                } else {
                                    $this->error("The limit tag's \"page\" property is not defined, but is required by the request declaration.");
                                }
                            }
                            if ('$page_length' === $length) {
                                if (array_key_exists("page_length", $tagsLimit)) {
                                    $length = $tagsLimit['page_length'];
                                } else {
                                    $this->error("The limit tag's \"page_length\" property is not defined, but is required by the request declaration.");
                                }
                            }
                        } else {
                            $this->error("The limit tag must be an array.");
                        }
                    } else {
                        $this->error("The limit tag was not set. It's required because this request uses The \$page and/or \$page_length variable in its limit expression.");
                    }
                }

                $offset = ((int)$page - 1) * (int)$length;
                $query->setLimit($offset, $length);
            }


            $joins = $requestDeclaration['joins'] ?? [];
            $where = $requestDeclaration['where'] ?? [];
            return $query;

        } else {
            $this->error("Some mandatory field is missing. It could be one either the  \"table\" property, or the \"base_fields\" property.");
        }
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * Throws an exception.
     *
     *
     * @param string $message
     * @throws ParametrizedSqlQueryException
     */
    protected function error(string $message)
    {
        throw new ParametrizedSqlQueryException($message);
    }


    /**
     * Returns an array of marker names (without the colon prefix) found in the given expression.
     *
     * @param string $expr
     * @return array
     */
    protected function findMarkers(string $expr): array
    {
        $ret = [];
        if (preg_match_all(self::$regMarker, $expr, $match)) {
            $ret = $match[1];
        }
        return $ret;
    }
}
