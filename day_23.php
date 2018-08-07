<?php
function join_matrix(array $matrix, bool $print = false)
{
    $out = '';
    foreach ($matrix as $row) {
        $out .= implode('', $row);
        if ($print === true) $out .= PHP_EOL;
    }
    return $out;
}
function expand_matrix(&$matrix, $rules)
{
    $subs = divide_matrix($matrix);
    $new_subs = [];
    // Check each of the divided matrices
    foreach ($subs as $sub) {
        $str = join_matrix($sub);
        // rotate 3 times. if still not match then flip
        // by its nature, 2x2 matrix will NOT need to flip
        $rotate = 0;
        while (!array_key_exists($str, $rules)) {
            if ($rotate === 3) {
                // Need to flip
                $sub = flip_matrix3($sub);
                $rotate = 0;
            } else {
                $sub = rotate_matrix($sub);
                $rotate++;
            }
            $str = join_matrix($sub);
        }
        array_push($new_subs, $rules[$str]);
    }
    // Join the new sub matrices
    $matrix = join_sub_matrices($new_subs);
}
function divide_matrix($matrix)
{
    $size = count($matrix);
    $subs = [];
    $sub_size = $size % 2 === 0 ? 2 : 3;
    for ($x = 0; $x < ($size/$sub_size); $x++) {
        for ($y = 0; $y < ($size/$sub_size); $y++) {
            $sub = [];
            for ($i = 0; $i < $sub_size; $i++) {
                for ($j = 0; $j < $sub_size; $j++) {
                    $sub[$i][$j] = $matrix[$i + $x*$sub_size][$j + $y*$sub_size];
                }
            }
            array_push($subs, $sub);
        }
    }
    return $subs;
}
function join_sub_matrices($subs)
{
    $ma = [];
    if (count($subs) === 1) return $subs[0];
    $size = sqrt(count($subs));
    $sub_size = count($subs[0]);
    $x = $y = 0;
    for ($i = 0; $i < $size*$sub_size; $i++) {
        for ($j = 0; $j < $size * $sub_size; $j++) {
            $idx = intval(floor($i/$sub_size))*$size + intval(floor($j/$sub_size));
            $ma[$i][$j] = $subs[$idx][$i%$sub_size][$j%$sub_size];
        }
    }
    return $ma;
}
function rotate_matrix($matrix)
{
    // https://stackoverflow.com/a/30088789
    array_unshift($matrix, null);
    $matrix = call_user_func_array('array_map', $matrix);
    return array_map('array_reverse', $matrix);
}
/**
 * Specific to this one, we only need to swap matrix row 0 and 2
 */
function flip_matrix3($matrix)
{
    $tmp = $matrix[0];
    $matrix[0] = $matrix[2];
    $matrix[2] = $tmp;
    return $matrix;
}
$rules = [];
$pat = [
    ['.', '#', '.'],
    ['.', '.', '#'],
    ['#', '#', '#']
];
$lines = file('inputd21.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $line) {
    $parts = explode(' => ', $line);
    // Output matrix
    $ma = explode('/', $parts[1]);
    $out = [];
    foreach ($ma as $m) {
        array_push($out, str_split($m));
    }
    $rules[str_replace('/', '', $parts[0])] = $out;
}
for ($i = 0; $i < 18; $i++) {
    expand_matrix($pat, $rules);
    if ($i === 4) {
        var_dump(substr_count(join_matrix($pat), '#'));
    }
}
var_dump(substr_count(join_matrix($pat), '#'));

?>