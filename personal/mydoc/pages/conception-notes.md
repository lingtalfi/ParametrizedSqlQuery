ParametrizedSqlQuery
======================
2019-08-12


This is a work in progress.



The parametrizedSqlQuery originates from a project originally called Realist, which ended up in the
[Light_Realist](https://github.com/lingtalfi/Light_Realist) repository (which might not yet be publicly available at the moment
you read those lines).


The main goal of this planet is to let external parameters (often coming from a gui) govern the creation
of the sql query, while still having the sql query written in a single location (so that the developer
doesn't have a hard time debugging).
Note: the developer would ideally have a config folder where all her parametrized requests will be (so that she doesn't 
need to open php class files to understand the code). It's all centralized.




And so for the developer's comfort we recommend using [babyYaml](https://github.com/lingtalfi/BabyYaml) files,
that's what I'll be using in this document to write my examples if I need to.



However, this planet doesn't care, it just wants an array called the **request declaration**, 
which represents the parametrized request.

Sql query structure reminder
-----------------
A regular **select** sql query consists of different parts, which order is important.
The parts are the following (in order):
(Note, I refer to mysql documentation, not 100% sure if this applies to sql in general)


- select clause
- from clause, including joins
- where
- group by
- having
- order by
- limit



Request declaration
-----------

The **request declaration** is an array with the following structure, which more or less corresponds to the
sql select structure above.

The request declaration use the concept of tag.
A tag is basically a like a token that an external actor (i.e. the gui) gives to us, and we do something about it (i.e. we 
modify the sql request accordingly).

Using tags allow us to have complete control on the sql request being generated, whilst providing the external actors
with a mean to trigger certain parts of the sql request.
 
When an external actor provides a tag, she generally needs to provide some accompanying parameters too.
So the couple tag/parameters is treated by this tool, each tag activating certain parts of the parametrized sql request below.

By convention, the tags we use start with the name of the functional section they are targeting (understand this from the examples in
the structure below).

 

The structure of the **request declaration** (aka parametrized request) is the following

- table: string. The table to fetch data from, including its alias if you need it (in joins).
    Examples: 
        - user u 
        - employee 
                
- base_fields: string|array. The columns to add to the query.
    Examples:
        - e.last_name
        - count(*) as total
        - u.pseudo
        
- base_join: string|array. An array of join expressions to add to the query. 
    Examples:
        - join_animal: inner join animal a on a.user_id=u.id
        
- base_group_by: string|array. An array of group by expressions to add to the query. 
    Examples:
        - e.last_name
        
- base_order: string|array. An array of order expressions to add to the query. 
    Examples:
        - u.pseudo asc
        - total desc
        
- base_having: string|array. An array of having expressions to add to the query. 
    Examples:
        - total < 200
        
        
        
- joins: array. Array of tag => join expression. 
    Examples:
        - join_animal: inner join animal a on a.user_id=u.id  
        
- where: array. Array of tag => where expression fragment.

            By default, all where expression fragments will be combined using the OR logical operator, as usually wants
            to search for something, and the OR logical operator gives more results than its more restrictive AND companion.
            
            An expression fragment can be either a string or an array.
            If it's a string, the equal **=** comparison operator will be used.
            
    Examples:
        - where_animal_name: a.name = :animal   
        - where_animal_type: a.type = :animal_type   
        - where_animal_price_less_than: a.price <= :price   
        - where_animal_price_more_than: a.price >= :price   
        - where_my_complicated_macro: (a.price between :price_low and :price.high) AND a.name like :animal   



- group_by: array. Array of tag => group by expression.

    Examples:
        - group_by_animal_name: a.name
        - group_by_animal_name_and_type: a.name, a.type
        
        
- having: array. Array of tag => having expression.        

    Examples:
        - having_animal_count: nb_animals > 6
        - having_animal_count: nb_animals > :nb_animal
        
- order: array. Array of tag => order expression. Notice that order doesn't require any user parameter.        

    Examples:
        - order_animal_name_asc: a.name asc
        - order_animal_name_desc: a.name desc
        
- limit: array. The limit array has the following structure:

        - page: int|string, the number of the page to display. Or the special value $page, which means that 
                the value will be provided by the user. 
        - length: int|string, the number of items to display. Or the special value $page_length, which means that
                the value will be provided by the user.
        
        
        Notice that we inject the parameters directly into the limit expression.          

        Examples: 
            - limit:
                - page: $page
                - length: $page_length
                
                                
    
    
- wiring: array. This section is explained in more details below.    






Note: this system will only be able to write mostly static requests.
For more dynamic requests which requires more application logic, this whole ParametrizedSqlQuery system is not suitable,
and one should use a more dynamical solution.



What is the user allowed to update?
-------------

In a typical gui, the user is allowed to manipulate the following sections:

- where (or having?)
- order
- limit


That's it.
Which means the other sections might be triggered by a wiring element (if the request needs it).

The strange good news is that as I'm implementing this thing in parallel, I see no use for wiring so far.
And that's good, because wiring makes it more complex. I wish it could be like that until the end, we will see...

 





The wiring section
------------------

This is a work in progress section (I'm having a hard time anticipate everything right now, I need more practise, 
so I will update this section as practise will tell me how to..., with time).
As long as you see this message, don't take the rest of this section seriously.


It turns out I don't need those features yet, but I might need them later.


The wiring section is special, it's not part of the sql request, however it tells the ParametrizedSqlQuery object
how to wire the request fragments together.
For instance, if the gui provides the where_animal_name parameter, we need to add the join_animal tag too.
Each parameter can **trigger** one or more other parameter. 
Further more, the expressions in the where section might require different parameters, and so we need to know which ones.
The wiring array basically solves all that.

It's an array of tagName => wiringDeclarations.
With wiringDeclarations being an array of sectionName => wiringSectionDeclaration
With:
- sectionName: the name of the section (base, joins, limit, ...) to operate on 
- wiringSectionDeclaration: array of tags (to trigger)



```yaml
# Wiring section
wiring:
    joins:
        where_animal_name: join_animal  // string|array (can require multiple joins)    
        where_animal_type: join_animal

    variables: # list of required variables
        where_animal_name: [animal]
        where_animal_type: [animal_type]
        where_my_complicated_macro: 
            - price_low
            - price_high
            - animal
        
```
                
Note to myself: the variables section should be an auto-detect.
The only parts that might have variables are:

- where       
- having

And the variables seem to always start with the colon.
Or use dollar if you believe other sections might use variables? or that where/having sections might use variables 
not prefixed with colons.

But I like the colon, because it's more sql readable.

       











Sources
--------

For group by reminder:
https://www.guru99.com/group-by.html

Group by more in depth:
https://www.youtube.com/watch?v=14qSQUpPoTQ


Samples used for testing:
https://dev.mysql.com/doc/employee/en/
 