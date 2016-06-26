<!--Расположение дуги:<br>
<img src="graph1.jpg" width="400" style="float: left;"><br>-->
<form>
    P<sub><small>0</small></sub>X = <input type="text" name="p0x" placeholder="P0.x"><br>
    P<sub><small>0</small></sub>Y = <input type="text" name="p0y" placeholder="P0.y"><br>
    P<sub><small>3</small></sub>X = <input type="text" name="p3x" placeholder="P3.x"><br>
    P<sub><small>3</small></sub>Y = <input type="text" name="p3y" placeholder="P3.y"><br>
    R&nbsp;&nbsp;&nbsp;&nbsp; = <input type="text" name="r" placeholder="R"><br>
    <input type="submit" value="Вычислить!">
</form>
<?php
$P0['x'] = $_GET['p0x']; //1
$P0['y'] = $_GET['p0y']; //11.2
$P3['x'] = $_GET['p3x']; //7
$P3['y'] = $_GET['p3y']; //13.5
$R       = $_GET['r'];   //4.15

function center_okr( $x1,$y1, $x2,$y2, $r ){
    $d = sqrt(($x1-$x2)*($x1-$x2) + ($y1-$y2)*($y1-$y2));
    $h = sqrt($r * $r - ($d/2) * ($d/2));
    $x01 = $x1 + ($x2 - $x1)/2 + $h * ($y2 - $y1) / $d;
    $y01 = $y1 + ($y2 - $y1)/2 - $h * ($x2 - $x1) / $d;
    $x02 = $x1 + ($x2 - $x1)/2 - $h * ($y2 - $y1) / $d;
    $y02 = $y1 + ($y2 - $y1)/2 + $h * ($x2 - $x1) / $d;
    $coords['x1'] = $x01;
    $coords['y1'] = $y01;
    $coords['x2'] = $x02;
    $coords['y2'] = $y02;
    return $coords;
}

function angle_point($ax, $ay, $bx, $by, $cx, $cy){
    $x1 = $ax - $bx;
    $x2 = $cx - $bx;
    $y1 = $ay - $by;
    $y2 = $cy - $by;
    $d1 = sqrt ($x1 * $x1 + $y1 * $y1);
    $d2 = sqrt ($x2 * $x2 + $y2 * $y2);
    return acos (($x1 * $x2 + $y1 * $y2) / ($d1 * $d2));
}

function peresechenie($a, $b, $r1, $c, $d, $r2){
    # формулы взяты отсюда: http://mathforum.org/library/drmath/view/51836.html
    #  (x-a)^2 + (y-b)^2 = R^2
    #  (x-c)^2 + (y-d)^2 = r^2
    #Let the centers be: (a,b), (c,d)
    #Let the radii be: r, s
    $r = $r1;
    $s = $r2;
    $e = $c - $a ;                     									#    [difference in x coordinates]
    $f = $d - $b ;                    									#    [difference in y coordinates]
    $p = sqrt(pow($e,2) + pow($f,2));                   				#    [distance between centers]
    $k = (pow($p,2) + pow($r,2) - pow($s,2))/(2*$p);   					#    [distance from center 1 to line joining points of intersection]
    $per['x1'] = $a + $e*$k/$p + ($f/$p)*sqrt(pow($r,2) - pow($k,2));
    $per['y1'] = $b + $f*$k/$p - ($e/$p)*sqrt(pow($r,2) - pow($k,2));
    $per['x2'] = $a + $e*$k/$p - ($f/$p)*sqrt(pow($r,2) - pow($k,2));
    $per['y2'] = $b + $f*$k/$p + ($e/$p)*sqrt(pow($r,2) - pow($k,2));
    return $per;
}

$round_center = center_okr( $P0['x'], $P0['y'], $P3['x'], $P3['y'], $R );
//echo "Центры окружностей: ( ".$round_center['x1']." ; ".$round_center['y1']." ) и ( ".$round_center['x2']." ; ".$round_center['y2']." ) <br>";
echo "Считаем для дуги окружности с центром ( ".round($round_center['x1'],3)." ; ".round($round_center['y1'],3)." )<br>";
$alpha = angle_point( $P0['x'], $P0['y'], $round_center['x1'], $round_center['y1'], $P3['x'], $P3['y']);
//echo "Угол Альфа равен: ".$alpha." рад.<br>";
$L = $R * 4/3 * tan(1/4*$alpha);
//echo "L = ".$L."<br>";
$G = sqrt ( $R*$R + $L*$L );
//echo "Гипотенуза(СP<sub>2</sub>) = ".$G."<br>";
$P1 = peresechenie($round_center['x1'], $round_center['y1'], $G, $P0['x'], $P0['y'], $L);
echo "P<sub><small>1</small></sub> = ( ".round($P1['x1'],3)." ; ".round($P1['y1'],3)." )<br>";
$P2 = peresechenie($round_center['x1'], $round_center['y1'], $G, $P3['x'], $P3['y'], $L);
echo "P<sub><small>2</small></sub> = ( ".round($P2['x2'],3)." ; ".round($P2['y2'],3)." )<br><br>";

echo "Считаем для дуги окружности с центром ( ".round($round_center['x2'],3)." ; ".round($round_center['y2'],3)." )<br>";
$P1 = peresechenie($round_center['x2'], $round_center['y2'], $G, $P0['x'], $P0['y'], $L);
echo "P<sub><small>1</small></sub> = ( ".round($P1['x2'],3)." ; ".round($P1['y2'],3)." )<br>";
$P2 = peresechenie($round_center['x2'], $round_center['y2'], $G, $P3['x'], $P3['y'], $L);
echo "P<sub><small>2</small></sub> = ( ".round($P2['x1'],3)." ; ".round($P2['y1'],3)." )<br>";
?>