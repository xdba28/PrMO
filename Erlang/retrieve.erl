% Retrieve first and last elements of a list
-module(retrieve).
-export([first/1, last/1]).

first([H|_])->
	H.

last([_|T]) when T /= []->
	last(T);

last([H|T]) when T == [] ->
	H.