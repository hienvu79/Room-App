<?php session_start();?>
<?php
    if($_SESSION["user"]!="admin"){
        header("location: login.php");
    }
?>
  <?php
  require_once('source/dbconnect.php');
  mysqli_set_charset($conn, 'UTF8');
  
    $sql = "SELECT *
    FROM green_contract t1 INNER JOIN green_room t2 ON t1.room_id = t2.room_id
                           INNER JOIN green_customer t3 ON t1.customer_id = t3.customer_id
                           INNER JOIN green_contract_log t4 ON t1.contract_id = t4.contract_id 
                           GROUP BY t1.room_id
                           ";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
      $rows = [];
    
      while ($row = mysqli_fetch_assoc($result)) {
        array_push($rows, $row);
    }
    
    } else {
      echo "";
    }
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <?php 
  include ("source/class.php");
  $p = new csdl();
  $q = new contract();
  ?>
  <head>
    <?php require_once 'block/block_head.php'?>
    <title>Hợp Đồng</title>
  </head>
<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php require_once 'block/block_menu.php';  ?>
    <!-- End of Sidebar -->
  </div>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Tình trạng hợp đồng</h1>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Hợp Đồng</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Tên khách hàng</th>
                      <th>Số phòng</th>
                      <th>Ngày thuê</th>
                      <th>Ngày kết thúc HĐ</th>
                      <th>Tình trạng</th>
                      <th>Đồng ý</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                    <?php 
                      if(empty($rows)){
                        echo "<tr><h3>Không có dữ liệu</h3></tr>";
                      }
                      else{
                        foreach($rows as $row){  
                          if($row['log_status']==1||$row['log_status']==2){
                    ?>
                    <tr>
                      <td><?php echo $row['customer_name']?></td>
                      <td><?php echo $row['room_name']?></td>
                      <td><?php echo $row['contract_datetime']?></td>
                      <td><?php echo $row['contract_expires']?></td>
                      <td><?php echo $row['log_content']?></td>
                      <td>
                        <?php 
                          if($row['log_status']==1){
                        ?>
                        <form><center><button class="btn btn-primary" formmethod="post" name="oke" type="submit" value="<?php echo $row['customer_id']?>">Oke</button></center></form>
                        <?php 
                          }
                          else{
                        ?>
                      <form><center><button class="btn btn-danger" formmethod="post" name="huy" type="submit" value="<?php echo $row['customer_id']?>">Oke</button></center></form>
                      <?php  }
                      ?>
                      </td>
                    </tr>
                      <?php 
                          }
                        }
                      }
                  ?>
                  </tbody>
                </table>
                <?php 
                if(!empty($row)){
                  $con_id  = $row['contract_id'];
                  if(isset($_POST['oke']) && $cus_id = $row['customer_id']){
                    {  
                      $con=$p->connect();
                      $q->giahan($con_id,$con);
                    }   
                  }
                  if(isset($_POST['huy']) && $cus_id = $row['customer_id']){
                    {  
                      $con=$p->connect();
                      $q->huy($con_id,$con);
                    }   
                  }
                }
                else echo"";
                  ?>   
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
     

  <?php require_once 'block/block_footer.php'; ?>
 <?php require_once 'block/block_foottag.php'; ?>

</body>

</html>
