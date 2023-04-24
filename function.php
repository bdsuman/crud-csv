<?php
const DB_FILE = 'data/students.csv';
class Student
{

    public static function setActive($setLink,$getLink='encode'){
            if($setLink==$getLink){
                return 'active';
            }else{
                return '';
            }
    }

    public static function seed():bool{
           $data = array(
                        array(
                            'id'=>1,
                            'name'=>'Suman',
                            'roll'=> 1
                        ),
                        array(
                            'id'=>2,
                            'name'=>'Sutopa',
                            'roll'=> 2
                        ),
                        array(
                            'id'=>3,
                            'name'=>'Sujon',
                            'roll'=> 3
                        ),
                        array(
                            'id'=>4,
                            'name'=>'Sazid',
                            'roll'=> 4
                        ),
                        array(
                            'id'=>6,
                            'name'=>'Rony',
                            'roll'=> 6
                        ),
                        array(
                            'id'=>7,
                            'name'=>'Khokon',
                            'roll'=> 7
                        ),
                        array(
                            'id'=>8,
                            'name'=>'Enamul',
                            'roll'=> 8
                        ),
                        array(
                            'id'=>9,
                            'name'=>'Rumon',
                            'roll'=> 9
                        ),
                        array(
                            'id'=>10,
                            'name'=>'Tanoy',
                            'roll'=> 10
                        ),
                    );
        $data_serialize = serialize($data);
        file_put_contents(DB_FILE,$data_serialize,LOCK_EX);
        return true;
    }

    public static function report():string{
        $data = file_get_contents(DB_FILE);
        $students = unserialize($data);
        $report ='';
        foreach($students as $key=>$student){
            $report.='<tr>
                        <td>'.++$key.'</td>
                        <td>'.$student['name'].'</td>
                        <td>'.$student['roll'].'</td>
                        <td><a href="index.php?type=edit&id='.$student['id'].'">Edit</a> | <a href="index.php?type=delete&id='.$student['id'].'" class="delete">Delete</a></td>
                    </tr>';
        }
        return $report;
    }

    public static function studentRollCheck(int $roll,int $id=null ):bool{
        $data = file_get_contents(DB_FILE);
        $students = unserialize($data);
        $duplicate = false;
        foreach($students as $_student){
            if($id==null){
                if($roll==$_student['roll']){
                    $duplicate = true;
                    break;
                }
            }else{
                if($roll==$_student['roll'] && $id!=$_student['id']){
                    $duplicate = true;
                    break;
                }
            }
        }
        return $duplicate;

    }

    public static function addStudent(string $name, int $roll ){
        $data = file_get_contents(DB_FILE);
        $students = unserialize($data);
        $id = max(array_column($students,'id'))+1;
        $new_student = array(
            'id'=>$id,
            'name'=>$name,
            'roll'=>$roll
        );
        array_push($students,$new_student);
        $serialize_data = serialize($students);
        file_put_contents(DB_FILE,$serialize_data,LOCK_EX);
        return true;
    }

    public static function updateStudent(int $id,string $name, int $roll ){
        $data = file_get_contents(DB_FILE);
        $students = unserialize($data);
        foreach($students as $key => $student){
            if($id==$student['id']){
                $students[$key]['name'] =$name;
                $students[$key]['roll'] =$roll;
                break;
            }
        }

        $serialize_data = serialize($students);
        file_put_contents(DB_FILE,$serialize_data,LOCK_EX);
        return true;
    }

    public static function deleteStudent(int $id ){
        $data = file_get_contents(DB_FILE);
        $students = unserialize($data);
        
        if(self::getStudent($id)){

            foreach($students as $key => $student){
                if($id==$student['id']){
                    unset($students[$key]);
                    break;
                }
            }
    
            $serialize_data = serialize($students);
            file_put_contents(DB_FILE,$serialize_data,LOCK_EX);
            return true;
        }else{
            return false;
        }
       
    }

    public static function getStudent(int $id ){
        $data = file_get_contents(DB_FILE);
        $students = unserialize($data);
        foreach($students as $student){
            if($id==$student['id']){
                return $student;
            }
        }
        return false;
    }


 

}


?>