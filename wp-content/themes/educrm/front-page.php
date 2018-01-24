<?php
// Including files responsible for creating account and login members.
include_once( get_parent_theme_file_path( '/app/account/account.php' ) );
if ( is_user_logged_in() ) {
	get_header( 'educrm' );
} else {
	get_header();
}
?>
<div class="section the_body">
	<div class="container">
	<div class="row">

	  <div class="col-md-5 forma"  id="login" >
		<h2 class="form-title text-center">Members Login </h2>
		<h3 class="form-title text-center"><sup>*</sup>Consultancy & Agents only</h3>

		<form class="form-horizontal" method="POST" action="<?php echo esc_url( home_url( '/#login' ) ); ?>">
		  <span class="message"><?php echo ( $login_message ) ?  $login_message  : ''; ?></span>
		  <div class="form-group">
			<label for="email" class="col-sm-3 control-label">Email</label>
			<div class="col-sm-9">
			  <input type="email" value="<?php echo ! empty( $login_message ) && ! empty( $_POST['email'] ) ? $_POST['email'] : ''; ?>"  name="email" class="form-control" id="email" placeholder="Email">
			</div>
		  </div>
		  <div class="form-group">
			<label for="password" class="col-sm-3 control-label">Password</label>
			<div class="col-sm-9">
			  <input type="password" name="password" class="form-control" id="password" placeholder="Password">
			</div>
		  </div>

		  <div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
			  <button type="submit" name="user_login" value="true" class="btn btn-primary">Sign in</button>
			</div>
		  </div>
		</form>
	  </div>

	  <div class="col-md-7 form"  id="registration">
		<h2 class="form-title text-center">Consultancy Registration</h2>
		<form class="form-horizontal" method="post" action="<?php echo home_url( '/#registration' ); ?>">

		  <span class="registration-message"><?php echo ( $registration_message ) ? $registration_message: ''; ?></span>
		  <div class="form-group">
			<label for="first_name" class="col-sm-4 control-label">First Name</label>
			<div class="col-sm-8">
			  <input type="first_name" value="<?php echo ! empty( $_POST['first_name'] ) ? $_POST['first_name'] : ''; ?>" name="first_name" class="form-control" id="first_name" placeholder="First Name">
			</div>
		  </div>

		  <div class="form-group">
			<label for="last_name" class="col-sm-4 control-label">Last Name</label>
			<div class="col-sm-8">
			  <input type="last_name" value="<?php echo ! empty( $registration_message ) && ! empty( $_POST['last_name'] ) ? $_POST['last_name'] : ''; ?>" name="last_name" class="form-control" id="last_name" placeholder="Last Name">
			</div>
		  </div>

		  <div class="form-group">
			<label for="email" class="col-sm-4 control-label">Email</label>
			<div class="col-sm-8">
			  <input type="email" name="email" value="<?php echo ! empty( $registration_message ) && ! empty( $_POST['email'] ) ? $_POST['email'] : ''; ?>" class="form-control" id="email" placeholder="Email">
			</div>
		  </div>

		  <div class="form-group">
			<label for="password" class="col-sm-4 control-label">Password</label>
			<div class="col-sm-8">
			  <input type="password" name="password" class="form-control" id="password" placeholder="Password">
			</div>
		  </div>


		  <div class="form-group">
			<label for="phone" class="col-sm-4 control-label">Phone</label>
			<div class="col-sm-8">
			  <input type="phone" value="<?php echo ! empty( $registration_message ) && ! empty( $_POST['phone'] ) ? $_POST['phone'] : ''; ?>" name="phone" class="form-control" id="phone" placeholder="phone">
			</div>
		  </div>

		  <div class="form-group">
			<label for="address" class="col-sm-4 control-label">Address</label>
			<div class="col-sm-8">
			  <input type="address" name="address" value="<?php echo ! empty( $registration_message ) && ! empty( $_POST['address'] ) ? $_POST['address'] : ''; ?>" class="form-control" id="address" placeholder="address">
			</div>
		  </div>

		  <div class="form-group">
			<label for="consultancy_name" class="col-sm-4 control-label">Consultancy Name</label>
			<div class="col-sm-8">
			  <input type="consultancy_name" value="<?php echo ! empty( $registration_message ) && ! empty( $_POST['consultancy_name'] ) ? $_POST['consultancy_name'] : ''; ?>" name="consultancy_name" class="form-control" id="consultancy_name" placeholder="Consultancy Name">
			</div>
		  </div>

		  <div class="form-group">
			<div class="col-sm-offset-4 col-sm-8">
			  <button type="submit" name="user_registration" value="true" class="btn btn-primary">Register</button>
			</div>
		  </div>
		</form>
	  </div>
	</div>
	</div>
</div>

<?php get_footer();
