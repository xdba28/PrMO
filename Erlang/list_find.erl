% Find the Nth element in the list
% 1st Parameter is list
% 2nd Parameter is list index
% List index starts with 1
-module(list_find).
-export([find_N/2, find_N/3]).

find_N(List, Num)->
	Count = 1,
	find_N(List, Num, Count).

find_N([_|T], Num, Count) when Num > Count ->
	find_N(T, Num, Count + 1);

find_N(List, Num, Count) when List == [] ->
	"Out of bound";

find_N([H|_], Num, Count) when Num == Count->
	H.