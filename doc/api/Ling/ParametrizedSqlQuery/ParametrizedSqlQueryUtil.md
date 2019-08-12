[Back to the Ling/ParametrizedSqlQuery api](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery.md)



The ParametrizedSqlQueryUtil class
================
2019-08-12 --> 2019-08-12






Introduction
============

The ParametrizedSqlQueryUtil class.

See [conception notes](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/pages/conception-notes.md) for more details.



Class synopsis
==============


class <span class="pl-k">ParametrizedSqlQueryUtil</span>  {

- Properties
    - protected static string [$regMarker](#property-regMarker) = !:([a-zA-Z_]+)! ;

- Methods
    - public [getSqlQuery](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/getSqlQuery.md)(array $requestDeclaration, array $tags = []) : [SqlQuery](https://github.com/lingtalfi/SqlQuery)
    - protected [error](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/error.md)(string $message) : void
    - protected [findMarkers](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/findMarkers.md)(string $expr) : array

}




Properties
=============

- <span id="property-regMarker"><b>regMarker</b></span>

    This property holds the regMarker for this instance.
    It's the regex used to extract the sql markers out of an expression.
    
    



Methods
==============

- [ParametrizedSqlQueryUtil::getSqlQuery](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/getSqlQuery.md) &ndash; Returns an SqlQuery instance parametrized using the given request declaration and params.
- [ParametrizedSqlQueryUtil::error](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/error.md) &ndash; Sets an error.
- [ParametrizedSqlQueryUtil::findMarkers](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/ParametrizedSqlQueryUtil/findMarkers.md) &ndash; Returns an array of marker names (without the colon prefix) found in the given expression.





Location
=============
Ling\ParametrizedSqlQuery\ParametrizedSqlQueryUtil<br>
See the source code of [Ling\ParametrizedSqlQuery\ParametrizedSqlQueryUtil](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/ParametrizedSqlQueryUtil.php)



SeeAlso
==============
Previous class: [ParametrizedSqlQueryException](https://github.com/lingtalfi/ParametrizedSqlQuery/blob/master/doc/api/Ling/ParametrizedSqlQuery/Exception/ParametrizedSqlQueryException.md)<br>
