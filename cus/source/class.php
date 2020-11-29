<?php
class csdl
{
	function connect()
	{
		$con=mysqli_connect("localhost","vnappmob","123456");
		if(!$con)
		{
			echo 'Không kết nối csdl';
			return false;
		}
		else
		{
			mysqli_select_db($con,"greenlight_motel_app");
			mysqli_set_charset($con, 'UTF8');
			return $con;
		}
	}
}
class taikhoan
{
	function login($user,$pass,$con)
	{
		$pass_md5=md5($pass);
		$sql="SELECT * FROM green_customer WHERE user_name = '$user';";
		$ketqua=mysqli_query($con,$sql);
		$total_row = mysqli_num_rows($ketqua);
		if($total_row > 0)
		{
			$row = mysqli_fetch_array($ketqua,MYSQLI_ASSOC);
			if($row['user_pass'] == $pass_md5)
			{
				$_SESSION["user"] = $row['user_name'];
				$_SESSION["customer_id"] = $row['customer_id'];
				header("location:index.php");
			}
			else
			{
				echo "<script> swal('Tên đăng nhập hoặc mật khẩu sai','Vui lòng nhập lại','error')</script>";
				return false;
			}
		}
		else
		{
			echo "<script> swal('Tên đăng nhập hoặc mật khẩu sai','Vui lòng nhập lại','error')</script>";
			return false;
		}
		
	}
}
class contract
{
	function giahan($con_id,$con)
	{
		$b = "UPDATE green_contract_log 
		SET log_status = '1', log_content = 'Gia hạn hợp đồng'
		WHERE contract_id = '$con_id'";
		mysqli_query($con,$b);
		echo "<script> swal('Oke','Bạn đã chọn gia hạn hợp đồng','success')</script>";
	}
	function huy($con_id,$con)
	{
		$a = "UPDATE green_contract_log 
		SET log_status = '2', log_content = 'Kết thúc hợp đồng'
		WHERE contract_id = '$con_id'";
		mysqli_query($con,$a);
		echo "<script> swal('Oke','Bạn đã chọn kết thúc hợp đồng','success')</script>";
	}
}
class customer
 {	
	function checknew($room_id,$fullname,$sdt,$cmnd,$ngaysinh,$join,$expires,$con)
	{		
		$sql="SELECT * FROM green_customer WHERE customer_identity='$cmnd'";
		$ketqua=mysqli_query($con,$sql);
		$i=mysqli_num_rows($ketqua);
		if ($i>0)
		{
			echo "<script> swal('Khách trọ đã thêm','Vui lòng chọn khách trọ khác','error')</script>";
			return false;
		}	
		if($fullname == ""||$sdt == ""||$cmnd == ""||$ngaysinh == ""||$join =="")
		{
			echo "<script> swal('Bạn chưa nhập đủ thông tin','Yêu cầu nhập đủ','warning')</script>";
			return false;
		}
		else
		{
			if (!is_numeric($sdt)||!is_numeric($cmnd)){
				echo "<script> swal('Lỗi','Vui lòng nhập số','error')</script>";
				return false;
			}
			else
			{
				$this->add_new($room_id,$fullname,$sdt,$cmnd,$ngaysinh,$join,$expires,$con);
				echo "<script> swal('Oke!','Thêm bạn trọ thành công','success')</script>";
			}
		}
		
	}
	function add_new($room_id,$fullname,$sdt,$cmnd,$ngaysinh,$join,$expires,$con)
	{
		$a="INSERT INTO green_customer(customer_name,customer_phone,customer_identity,customer_birthday,user_name,user_pass)
		VALUES('$fullname','$sdt','$cmnd','$ngaysinh','','')";
		$b="UPDATE green_room SET room_status='1', available_date='$expires'
		WHERE room_id ='$room_id'";
		mysqli_query($con,$b);
		if ($con->query($a) === TRUE){
			$cus_id = $con->insert_id;
			$c="INSERT INTO green_contract(customer_id,room_id,contract_datetime,contract_expires) VALUES('$cus_id','$room_id','$join','$expires')";
			if ($con->query($c) === TRUE){
				$con_id = $con->insert_id;
				$d = "INSERT INTO green_contract_log(contract_id,log_content,log_status) VALUES('$con_id','đang thuê','0')";
				mysqli_query($con,$d);
			}
		}
		else
		{
			echo 'Không thành công. Lỗi' . $con->error;
		}
	}
}
?>