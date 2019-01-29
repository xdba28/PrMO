-module(loop).
-export([while/1]).

% if empty out zero
while([])-> 0;


while([H|T])->
	io:fwrite("~w~n", [T]),
	H + while(T).