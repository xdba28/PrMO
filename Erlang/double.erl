% Double each value in a list
-module(double).
-export([list_double/1]).

list_double([])-> [];

list_double([H|T])->
	[H * 2| list_double(T)].