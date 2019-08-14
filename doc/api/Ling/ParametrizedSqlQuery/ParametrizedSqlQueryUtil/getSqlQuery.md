[Back to the Ling/ParametrizedSqlQuery api](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery.md)<br>
[Back to the Ling\ParametrizedSqlQuery\ParametrizedSqlQueryUtil class](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil.md)


ParametrizedSqlQueryUtil::getSqlQuery
================



ParametrizedSqlQueryUtil::getSqlQuery — Returns an SqlQuery instance parametrized using the given request declaration and params.




Description
================


public [ParametrizedSqlQueryUtil::getSqlQuery](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/getSqlQuery.md)(array $requestDeclaration, array $tags = []) : [SqlQuery](https://github.com/lingtalfi/SqlQuery)




Returns an SqlQuery instance parametrized using the given request declaration and params.
Or throws an exception if something wrong occurs.

For more information about the request declaration structure, see [the conception notes](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/pages/conception-notes.md).




Parameters
================


- requestDeclaration

    

- tags

    Array of tag => parameters.


Return values
================

Returns [SqlQuery](https://github.com/lingtalfi/SqlQuery).


Exceptions thrown
================

- [ParametrizedSqlQueryException](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/Exception/ParametrizedSqlQueryException.md).&nbsp;







Source Code
===========
See the source code for method [ParametrizedSqlQueryUtil::getSqlQuery](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/ParametrizedSqlQueryUtil.php#L46-L214)


See Also
================

The [ParametrizedSqlQueryUtil](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil.md) class.

Next method: [error](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/error.md)<br>

