
<?php

$mypic = $_FILES['upload']['name'];
$temp 	= $_FILES['upload']['tmp_name'];
$type = $_FILES['upload']['type'];


$name = $_POST['name'];
$last = $_POST['last'];
$house = $_POST['house'];
$street = $_POST['street'];
$phone = $_POST['phone'];
$sex = $_POST['sex'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$d_id = $_POST['d_id'];
$b_id = $_POST['b_id'];
$designation = $_POST['designation'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];

$pincode = $_POST['pincode'];
$city = $_POST['city'];
$state = $_POST['state'];
$salary = $_POST['salary'];

$bankname = $_POST['bankname'];
$accountnum = $_POST['accountnum'];
$f=0;
$url=parse_url(getenv("CLEARDB_DATABASE_URL"));    $server = $url["host"];   $username = $url["user"];   $password1 = $url["pass"];   $db = substr($url["path"],1);   $con= mysqli_connect($server, $username, $password1) or die("Problem with connection...");
mysqli_select_db($con,$db) or die(mysqli_error($con));

$query = mysqli_query($con, "SELECT * FROM emp_account");
while($row=mysqli_fetch_assoc($query))
{
	$acc_num=$row['ACCOUNT_NUMBER'];
	if($accountnum==$acc_num)
	{
		echo "Bank Account Number already exists<br />
		Please enter another valid Account Number<br />";
		$f=1;
	}
}
if ($name && $last && $house && $street && $phone && $sex && $email && $dob && $d_id && $b_id && $designation && $password && $cpassword && $pincode && 
$city && $state && $salary && $bankname && $accountnum && $f==0) {

if (preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
	
    if(preg_match("/^[0-9]{10}$/", $phone)){
		
	if (strlen($password) > 5) {

			if ($password == $cpassword) {

				$url=parse_url(getenv("CLEARDB_DATABASE_URL"));    $server = $url["host"];   $username = $url["user"];   $password1 = $url["pass"];   $db = substr($url["path"],1);   $con= mysqli_connect($server, $username, $password1) or die("Problem with connection...");
				mysqli_select_db($con,$db) or die(mysqli_error($con));

            $username = mysqli_query($con, "SELECT phone_number FROM employee WHERE phone_number='$phone'");
            $count = mysqli_num_rows($username);
            $remail = mysqli_query($con, "SELECT email_id FROM employee WHERE email_id='$email'");
            $checkemail = mysqli_num_rows($remail);

            if ($checkemail != 0) {

                echo "This email is already registered! Please type another email...";
            } else {


                if ($count != 0) {

                    echo "This name is already registered! Please type another name";
                } else {
					
					if(($type=="image/jpeg") || ($type=="image/jpg") || ($type=="image/bmp")){
						
						
						$dir = "empprofiles/$name/images/";
						mkdir($dir, 0777, true);
						move_uploaded_file($temp, "empprofiles/$name/images/$mypic");
						echo "This will be you profile picture!<p><img border='1' width='50' height='50' src='empprofiles/$name/images/$mypic'><p>";
						$passwordmd5 = md5($password);
						mysqli_query($con, "INSERT INTO employee(first_name,last_name,house_number,street,phone_number,sex,email_id,dob,d_id,password) VALUES('$name','$last','$house','$street',$phone,'$sex','$email','$dob','$d_id','$passwordmd5')");
						mysqli_query($con, "INSERT INTO pin_city(pincode,city) VALUES('$pincode','$city')");
						mysqli_query($con, "INSERT INTO pin_state(pincode,state) VALUES('$pincode','$state')");
						mysqli_query($con, "INSERT INTO emp_pin(pincode) VALUES('$pincode')");
						mysqli_query($con, "INSERT INTO bank_details(bank_name,account_number) VALUES('$bankname','$accountnum')");
						mysqli_query($con, "INSERT INTO emp_account(account_number) VALUES('$accountnum')");
						mysqli_query($con, "INSERT INTO emp_salary(salary) VALUES('$salary')");
						mysqli_query($con, "INSERT INTO emp_designation(designation) VALUES('$designation')");
						mysqli_query($con, "INSERT INTO has(d_id,branch_id) VALUES('$d_id','$b_id')");
						mysqli_query($con, "INSERT INTO works_for(d_id) VALUES('$d_id')");
						
						echo "You have succefully registered!<a href='emp_home.php'>Login now!</a>";
					
					} else {
					
						echo "Please load a valid jpeg, jpg or bmp! And size must be less than 10k!";
					
					}
                }
            }
        } else {
            echo "Your passwords don't match!";
        }
    } else {

        echo "Your password is too short! You need to type a password between 4 and 15 charachters!";
    }
	}else
	{
		echo "Please type a valid phone number!";
	}		
	}else{
			echo "Please type a valid email!";
	}
	
} else {
    echo "you have to complete the form!";
}


?>