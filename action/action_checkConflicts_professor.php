<?php
require_once '../Database.php';
session_start();

$database = new Database();
$dbh = $database->getdbh();

    $checkConflicts = $dbh->prepare(
        "  SELECT s1.section_id id_1, s1.prof_id profId_1,
          s1.section_start_time start_1, s1.section_end_time end_1,
          s2.section_id id_2,
          s2.section_start_time start_2, s2.section_end_time end_2
    FROM W01143557.Section s1 LEFT JOIN W01143557.Section s2
    ON s1.section_days LIKE CONCAT('%',s1.section_days,'%')
    AND s1.sem_id = s2.sem_id
    AND s1.prof_id = s2.prof_id
    AND s1.section_start_time BETWEEN s2.section_start_time AND s2.section_end_time
    WHERE NOT s1.section_id = s2.section_id
    AND s1.sem_id = {$_SESSION['mainSemesterId']}
    AND s1.section_is_online = 0
    ORDER BY s1.section_id;");
    try{
        $checkConflicts->execute();

        $conflicts = $checkConflicts->fetchAll();
        $conflicts_json = [];
        $secId_1 = 0;

        foreach($conflicts as $index=>$conflict){
            if ($secId_1 != $conflict['id_1']){
                $secId_1 = $conflict['id_1'];
                $conflicts_json[$index] = array(
                    'secId_1'=>$conflict['id_1'],
                    'secId_2'=>$conflict['id_2'],
                    'profId'=>$conflict['profId_1']
                );
            }
        }
        echo json_encode($conflicts_json);
    }catch(Exception $e){
        $message = "action_checkConflicts_professor: ".$e->getMessage();
        echo $message;
    }



