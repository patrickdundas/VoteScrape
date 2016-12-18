<?php
//configuration
$choice1weight = 100;
$choice2weight = 80;
$choice3weight = 60;
$choice4weight = 40;
$choice5weight = 20;

//columns to scan (assuming start index is 0)
$start = 1;
$end = 5;

//does row 1 contain headers (not votes?)
$excludefirstrow = true;

//CSV filename

$csvfile = 'test2.csv';

//read csv spreadsheet file as array
$votearray = array_map('str_getcsv', file($csvfile));

//DEBUG - print out array
/*echo "<pre>";
var_dump($votearray);
echo "</pre>";
echo "<br/><br/><br/>";*/

//should the first row be deleted from the vote count? Determined by configuration.
if($excludefirstrow == true){
    unset($votearray[0]);
}

//create blank array to add score count arrays to (generated at end of each loop)
$allscores = array();

//start loop at designated starting ($start) column
$i = $start;
while($i <= $end){
  ${'a'.$i} = array(); //create blank array to fill with choice 1 votes

  foreach ($votearray as ${'ar'.$i}) { //loop over entire spreadsheet
    ${'a'.$i}[] = ${'ar'.$i}[$i]; //find people's first choice votes and log them into array $a
  }

  //tally up names (count the votes for each person)
  ${'vote'.$i.'tally'}= array_count_values(${'a'.$i});

  //DEBUG - show choice 1 vote count
  /*echo "<br/>VOTE TALLY ";
  print_r(${'vote'.$i.'tally'});*/

  //for each person, multiply by choice 1 weight ($choice1weight)
  ${'choice'.$i.'vote'} = array(); //create final choice1 vote count with scores based on weight
  foreach (${'vote'.$i.'tally'} as $key => $value) { //loop over values from the choice 1 tally
    ${'choice'.$i.'vote'}[$key] = $value * ${'choice'.$i.'weight'}; //multiply each tally value by the score
  }

  //DEBUG - show choice1 vote with weighted scores
  /*echo "<br/>SCORED TALLY ";
  echo"<pre>".var_dump(${'choice'.$i.'vote'})."</pre>";*/

  //add this score data to the overall score array ($allscores) for later score addition
  array_push ($allscores, ${'choice'.$i.'vote'});
  $i++;
}

function array_sum_identical_keys() {
    $arrays = func_get_args();
    $keys = array_keys(array_reduce($arrays, function ($keys, $arr) { return $keys + $arr; }, array()));
    $sums = array();

    foreach ($keys as $key) {
        $sums[$key] = array_reduce($arrays, function ($sum, $arr) use ($key) { return $sum + @$arr[$key]; });
    }
    return $sums;
}


//add up all scores for each choice (they have already been weighted in the loop)
$finalresult = call_user_func_array('array_sum_identical_keys', $allscores);

//DEBUG - Show final result (raw)
//var_dump($finalresult);

foreach ($finalresult as $key => $value) {
  echo $key.": ".$value."<br/>";
}



?>
