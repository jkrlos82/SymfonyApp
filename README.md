# Symfony Test Task #
We are looking for a symfony service that will provide you with a place (given as name) checks for various
criterias.
The output is an AND expresion of all criteria (so true if all criteria are met) as well as a list of all criterion
and their Boolean status.
It should be easy to add new criteria or disable existing criteria. In addition, there should be a defined order of
the implemented criterion.

## Criteria Check ##

The following criteria should be run through for a location in the order from top to bottom.
- naming: The place name has an odd number of letters.
- daytemp: If it is currently night (between sunset and sunrise) and the temperature is between 10 and 15 degrees Celcius. Or it is daytime and the temperature is between 17 and 25 degrees Celcius.
- rival: It is currently warmer at the given place than in location "Köln".

## Call ##

In the Symfony application there is a REST endpoint /check , which gets the name of a city as a query
parameter city= .
As a result, a JSON with check=bool and criteria=[alias=>bool] should be returned in case of
success. So, for example (Pseudo code)

[
    check => false,
    criteria => [naming=>true, daytemp=>false, rival=>true]
]

Return in case of an error JSON string error=true .
