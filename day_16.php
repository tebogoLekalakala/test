<?php
function spin(&$array, $length)
{
    $move = array_splice($array, $length * -1);
    $array = array_merge($move, $array);
}
function exchange(&$array, $pos1, $pos2)
{
    $tmp = $array[$pos2];
    $array[$pos2] = $array[$pos1];
    $array[$pos1] = $tmp;
}
function partner(&$array, $a, $b)
{
    $a_pos = array_search($a, $array);
    $b_pos = array_search($b, $array);
    exchange($array, $a_pos, $b_pos);
}
function make_moves(&$array, $moves)
{
    foreach ($moves as $move) {
        switch ($move[0]) {
            case 's':
                $length = intval(substr($move, 1));
                spin($array, $length);
                break;
            case 'x':
                $ins = explode('/', substr($move, 1));
                exchange($array, $ins[0], $ins[1]);
                break;
            case 'p':
                $ins = explode('/', substr($move, 1));
                partner($array, $ins[0], $ins[1]);
                break;
        }
    }
}
$arr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p'];
$moves = explode(',', file_get_contents('input.txt'));
make_moves($arr, $moves);
echo implode('', $arr) . PHP_EOL;
$count = 1;
while (1) {
    make_moves($arr, $moves);
    $count++;
    if (implode('', $arr) === 'abcdefghijklmnop') break;
}
for ($i = 0; $i < (1000000000 % $count); $i++) {
    make_moves($arr, $moves);
}
echo implode('', $arr) . PHP_EOL;
?>