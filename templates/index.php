<?php get_header(); ?>

<div id="login-box">
	<h1>Login</h1>

	<form action="" method="post">
		<?php if ( ! empty( $errors ) ) { ?>
			<p class="error">
				<?php 
					foreach ( $errors as $key => $error ) {
						echo $error . '<br>';
					}
				?>
			</p>
		<?php } ?>
		<p>
			<label for="username">Usuário<br>
				<input type="text" name="username" id="username" class="input" value="" size="20">
			</label>
		</p>
		<p>
			<label for="userpass">Senha<br>
				<input type="password" name="userpass" id="userpass" class="input" value="" size="20">
			</label>
		</p>
		<p>
			<button class="button button-primary">Entrar</button>
		</p>
		<input type="hidden" name="login" value="1">
	</form>

</div>

<?php get_footer(); ?>