<?php

	echo '
<!DOCTYPE html>
<html lang="ru">
<script language="JavaScript">

function tabSize(x, y) { //���������� �������� ������� � ����������� �� �������� ������� ���� �������� � ����������� ���������.

	let scrRate = window.innerWidth / window.innerHeight;
	let tableWidthpercent = 98; //������� ������ ����, ���������� ��������.
	let xyRate = ((x / y)||1)||1;
	let tabH = (xyRate < scrRate) ? (tableWidthpercent / scrRate):(tableWidthpercent / xyRate);
	let tabW = (xyRate < scrRate) ? (xyRate * tableWidthpercent / scrRate):tableWidthpercent;
	return [tabW, tabH];
}


let rectArrW; //�������������� ������ �������.
let rectArrH; //������������ ������ �������.

function tabResize () { //��������� ������ � ������ �������.
        
	let tabWH = tabSize(rectArrW, rectArrH);
	let tab = document.getElementsByTagName(\'table\')[0];
	tab.style.width = tabWH[0] + \'vw\';
	tab.style.height = tabWH[1] + \'vw\';
    }


window.onresize = tabResize; 

</script>
<body>
';

//������ ���� � ������������ ����.���. � ����.���. ����� ��������������� � ������� 'X Y X Y' ��������������. 
//����� ������ ����� - ����� �������.  

	$file = file('rect.txt'); 

	foreach ($file as $str) {
		$rectArr[] = explode(' ', $str);
	}
	unset($str);

//--------------------------------------------------

	$zeroPoint = 1;	// �������� �� �� ����� ������ ���������. 1-��, 0-���.

	$x[] = 0;
	$y[] = 0;

//���������� ������ ��������� �� ������ ���. (����� � ����� x � w ��������������, y � h - ������.)

	foreach ($rectArr as $r) {

		array_push($x, (int)$r[0], (int)$r[2]);
		array_push($y, (int)$r[1], (int)$r[3]);
	}
	unset($r);

	sort($x, SORT_NUMERIC);
	sort($y, SORT_NUMERIC);

//-----------------------------------------------------

	$tempRect = array();	
	$dim = null;
	$w = null;
	$h = null;
	$isRect = '';
	$rectArrW = end($x);
	$rectArrH = end($y);

	$tab = '<table style="margin: 0px auto auto auto; border-spacing: 0px;" border="1px">';


//�������� ������� ������� "���������"
	
	$skipW = 0;

	for ($row = ($zeroPoint)?0:1; $row < sizeof($y) - 1; $row++) {
				
		if ($y[$row] == $y[$row + 1]) {continue;} //������� ���������� ��������
		
		$tempRect = array();
		$tab = $tab . '<tr>';
    
	//��������� ��������� ������ ���������������, ���������� � ������� "������"
		foreach ($rectArr as $rect) {

			if ($rect[1] <= $y[$row] && $rect[3] > $y[$row]) {
				
				$tempRect[] = $rect;
			}
		}
        	unset($rect);
        
		//�������� �� ������ "������" "������"

		$skipH = 0;

		for ($col = ($zeroPoint)?0:1; $col < sizeof($x) - 1; $col++) {
			
			if ($x[$col] == $x[$col + 1]) {continue;} //������� ���������� ��������
			
			$isRect = '';

			//������ ������ �� ������������ ��� ��������� � �� �������������� �� ���������� �������
			
			foreach ($tempRect as $rect) {				
				
				if ($rect[0] <= $x[$col] && $rect[2] > $x[$col]) {
		
					$isRect = ' bgcolor="#994400"';
				}							
			}
			unset($rect);
            		//--------------------------------------------------------------------------------------
			
			//��������� ������� �����

			$dim = ' style="';

			if ($skipW == 0) {

				$w = ' width:' . (100 * ($x[$col + 1] - $x[$col]) / end($x)) . '%;';
			}

			if ($skipH == 0) {

				$h =' height:' . (100 * ($y[$row + 1] - $y[$row]) / end($y)) . '%;';
			}

			if ($skipW == 0 || $skipH == 0) {

				$dim = $dim . $w . $h . '"';				
				$w = '';
				$h = '';

			}else {

				$dim = '';
			}
			//----------------------

			$tab = $tab . '<td' . $dim . $isRect . '></td>';
			$skipH++;
						
		}

		$tab = $tab . '</tr>';
		$skipW++;

	}
	$tab = $tab . '</table>';
	echo $tab;

	echo '
<script language="JavaScript">


	rectArrW = ' . $rectArrW . ';
	rectArrH = ' . $rectArrH . ';


	window.onload = function(){

				tabResize();
	}

</script>
</body>
</html>';

?>
