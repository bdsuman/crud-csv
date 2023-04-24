<?php
require_once ('function.php');

$type = (!empty($_GET['type']) || isset($_GET['type']))?$_GET['type']:'report';


if('seed'==$type){
    if(Student::seed()){
        header('location:index.php?type=report&success_msg=Successfully Seeding Complate.');
    }
}
if('delete'==$type){
    $id = htmlspecialchars($_GET['id']);
    if(Student::deleteStudent($id)){
        header('location:index.php?type=report&success_msg=Successfully Deleted.');
    }else{
        $error_msg='Stundent Not Found in Database.';
    }
}
$name = '';
$roll = '';
if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $roll = htmlspecialchars($_POST['roll']);
    $id = htmlspecialchars($_POST['id']);
    if (!empty($name) && !empty($roll)) {
      
        if(!empty($id)){
            if(!Student::studentRollCheck($roll,$id)){
                Student::updateStudent($id,$name,$roll);
                header('location:index.php?type=report&success_msg=Successfully Edited Student.');
            }else{
                $error_msg='Duplicate Roll Found in Database.'; 
            }
        }else{
            if(!Student::studentRollCheck($roll)){
                Student::addStudent($name,$roll);
                header('location:index.php?type=add&success_msg=Successfully Added Student.');
            }else{
                $error_msg='Duplicate Roll Found in Database.';
            }
        }
       
    } else {
        $error_msg='Name and Roll field is required.';
    }
}


?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>.::CRUD - CSV::.</title>
</head>

<body>
    <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">CRUD - CSV</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo Student::setActive('report',$type); ?>" aria-current="page" href="index.php?type=report">All Student</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo Student::setActive('add',$type); ?>" href="index.php?type=add">Add Student</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo Student::setActive('seed',$type); ?>" href="index.php?type=seed">Seed</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
        <div class="card mt-5">
            <div class="card-header">
              CRUD - CSV - Project </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- <h5 class="card-title" style="border-bottom: 1px solid #bfbfbf;">Task Active: <span style="color:greenyellow; font-weight:bold;"><?php //echo ucwords($type);?></h5> -->
                        <?php
                        // $error ='ok';
                        if (isset($error_msg)) {
                            echo <<<alert_error
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Caution!!</strong> {$error_msg}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                alert_error;
                        }
                       
                        if (isset($_GET['success_msg'])) {
                            echo <<<alert_succes
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Congratulations!!</strong> {$_GET['success_msg']}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    alert_succes;
                        }
                        ?>
                        <?php
                        if('report'==$type){

                        ?>
                            <table class="table table-success table-striped">
                                    <thead>
                                        <th>Serial</th>
                                        <th>Name</th>
                                        <th>Roll</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                         echo Student::report();
                                        ?>
                                    </tbody>
                            </table>
                        <?php
                        }
                        if('add'==$type){
                        ?>
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Student Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" value="<?php echo $name;?>" placeholder="Write Name here.">
                                </div>
                                <div class="mb-3">
                                    <label for="roll" class="form-label">Student Roll <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="roll" name="roll" value="<?php echo $roll;?>" placeholder="Write Roll here.">
                                </div>
                                <div align="center">
                                    <a href="index.php?type=report" class="btn btn-success">Back</a>
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" name="submit" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                        <?php
                        }
                        if('edit'==$type && isset($_GET['id'])):
                            $id = htmlspecialchars($_GET['id']);

                           if($student=Student::getStudent($id)):
                        ?>
                         <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Student Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" value="<?php echo $student['name'];?>" placeholder="Write Name here.">
                                    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" >
                                </div>
                                <div class="mb-3">
                                    <label for="roll" class="form-label">Student Roll <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="roll" name="roll" value="<?php echo $student['roll'];?>" placeholder="Write Roll here.">
                                </div>
                                <div align="center">
                                    <a href="index.php?type=report" class="btn btn-success">Back</a>
                                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                    <button type="reset" name="submit" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                        <?php
                            endif;
                        endif;
                        ?>
                    </div>
                </div>

            </div>
            <div class="card-footer text-muted">
                Project by: Suman Sen  Email: <a href="mailto:mesuman@yahoo.com">mesuman@yahoo.com</a> &amp; GitHub: <a href="https://github.com/bdsuman">bdsuman</a>
            </div>
        </div>
    </div>
    <script>
        var elements = document.getElementsByClassName("delete");

            var deleteConfirm = function(event) {
               if(confirm('Are You Sure?')){
                console.log('deleted data');
               }else{
                event.preventDefault();
                console.log('keep data');
               }
            };

            for (var i = 0; i < elements.length; i++) {
                elements[i].addEventListener('click', deleteConfirm, false);
            }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>