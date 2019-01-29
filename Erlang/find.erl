-module(find).
-export([find_N/2, find_N/3]).


find_N(List, Tofind)->
	Counter = 1,
	find_N(List, Tofind, Counter).

find_N([_|T], Tofind, Counter) when Tofind > Counter ->
	find_N(T, Tofind, Counter+1);
find_N(List, Tofind, Counter) when List == [] ->
	"Out of bound";
find_N([H|_], Tofind, Counter) when Tofind == Counter->
	H.


