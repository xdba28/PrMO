-module(list).
-export([merge/2]).

merge([], [])-> [];

% if 2nd list is empty
merge([H | T], [])->
	[H | merge(T, [])];

% if 1st list is empty
merge([], [H | T])->
	[H | merge(T, [])];

merge([H | T], [H2 | T2])->
	[H, H2 | merge(T, T2)].