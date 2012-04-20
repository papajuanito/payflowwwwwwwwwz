<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<section id="reclutamiento_section">
	<h2>Centro de Reclutamiento</h2>
	<div id="reclutamiento_overview">
		<div id="social_btns">
			<a id="fb_btn" href="#">Facebook</a>
			<a id="twitter_btn" href="<?php echo (empty($_GET['oauth_verifier']))? site_url('social/twitter_oauth_process') : '#'?>">Twitter</a>
			<a id="contact_btn" href="#">Contact</a>
		</div>
		<p>¡Bienvenido a tu Centro de Reclutamiento! Aquí podrás ver tus guerreros recomendados e invitar a tus amigos a tráves de Facebook, Twitter y correo electrónico. Mientras más guerreros reclutes más trofeos y rangos vas a ganar.</p>
	</div>
	
	<div id="guerreros_recomendados">
		<h2>Guerreros Recomendados</h2>
		<!--div id="thumbs_nav">
			<a id="on_prev" href="#">Previous</a>
			<a id="on_next" href="#">Next</a>
		</div-->
		
		<?php if (empty($recommended)) : ?>
			<div class="no_items_warning">
				<div id="warning"></div>
				<h3>No tienes ningun guerrero pendiente de invitar.</h3>
				<p>Comienza a reclutar guerreros por correo electrónico y expande tu ejercito de luz.</p>
			</div>
		<?php endif; ?>
		
		<ul id="recomended_warriors">
		<?php foreach ($recommended as $i => $person) : ?>
			<li <?php if (($i % 3) == 2) {?>class="right_side" <?php }?>>
				<span class="badge_circle <?php echo $person->legion_style; ?> <?php echo $person->rank_style; ?>"></span>
				<img src="<?php echo avatar_url ($person->guerrero_avatar); ?>" class="user_pic" alt="thumbnail">
				<h4><?php echo $person->guerrero_name; ?></h4>
				<p><span>Legión:</span> <?php echo lang ('app_'. $person->legion_tag .'_name'); ?></p>
				<a href="javascript:;" class="gue_invite green_btn" data-guerrero_id="<?php echo $person->guerrero_id; ?>">ÚNETE A MI EJERCITO</a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	
	<div id="guerreros_de_facebook">
		<h2>Guerreros de Facebook</h2>
		<!--div id="thumbs_nav">
			<a id="on_prev" href="#">Previous</a>
			<a id="on_next" href="#">Next</a>
		</div-->
		
		<ul id="facebook_warriors"></ul>
	</div>
	
	<div id="guerreros_de_twitter" data-twitter-on="<?php echo (isset($_GET['oauth_verifier']))? 'ON': '' ?>">
		<h2>Guerreros de Twitter</h2>
		<!-- <div id="thumbs_nav">
			<a id="on_prev" href="#">Previous</a>
			<a id="on_next" href="#">Next</a>
		</div> -->
		<ul id="twitter_warriors">
		<?php 		 
		if(!empty($_GET['oauth_verifier']) && $this->session->userdata('oauth_token') && $this->session->userdata('oauth_token_secret') ) 
		{
			if($this->session->userdata('oauth_verifier') &&  $_GET['oauth_verifier'] == $this->session->userdata('oauth_verifier') ) 
			{
				redirect('social/twitter_oauth_process');
			}
			else{
				
				$this->Guerrero_model->update_trophies($this->guerrero->guerrero_id, $this->guerrero->guerrero_rank_id, 'soc');

				$this->session->set_userdata('oauth_verifier',$_GET['oauth_verifier'] );
				$twitteroauth = new TwitterOAuth($consumer_key, $consumer_secret, $this->session->userdata('oauth_token') , $this->session->userdata('oauth_token_secret') );
				$access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']); 
				$this->session->set_userdata('access_token', $access_token);
				//$user_info = $twitteroauth->get('account/verify_credentials'); 
				
				
				$friends_ids = $twitteroauth->get('followers/ids');
				$max_friends = count($friends_ids->ids);
				
				$top_count = 21;
				
				if( $max_friends < $top_count){
					$top_count = $max_friends;
				}
				
				$mixed_friends = array_rand($friends_ids->ids, 21);
				$ids_string ='';
				for($i = 0 ; $i< $top_count ; $i++) 
				{ 
					if($i+1 < $top_count)
					{
						$ids_string .=  $friends_ids->ids[$mixed_friends[$i]].",";
					}
					else
					{
						$ids_string .=  $friends_ids->ids[$mixed_friends[$i]];
					}
					
					
				}
				
				$users_random = $twitteroauth->get('users/lookup', array('user_id'=>$ids_string) );
				$i = 0;
				
				$tweet = "Únete a mi ejercito en Guerreros de Luz, una comunidad guerreros en contra de la trata humana. http://guerrerosdeluz.org/ via @RM_Foundation";
				foreach ($users_random as $user){ 
				?>
					
					<li <?php if (($i % 3) == 2) : ?>class="right_side" <?php endif; ?>>
						<img src="<?php echo $user->profile_image_url ?>" class="user_pic" alt="thumbnail">
						<h4><?php echo $user->name ?></h4>
							<a data-follower_id = "<?php echo $user->id ?>" href="javascript:;" class="green_btn">INVITAR GUERRERO</a>
<!-- 						<a href="https://twitter.com/intent/tweet?screen_name=<?php echo $user->screen_name?>&text=<?php echo $tweet?>" class="green_btn">INVITAR GUERRERO</a> -->
					</li>
				
				<?php  $i++;} //endforeach <?php echo site_url('social/twitter_send_message/'.$user->id) 
				
			}
		}
		

		?>
		
		</ul>
	</div>
	
	<div id="invitar_via_email">
		<h2>Invitar via Email</h2>
		
		<div id="send_message">
			<?php echo form_open_multipart ('social/send_email_invites'); ?>
				<fieldset>
					<label for="email_from">From:</label>
					<input type="text" name="email_from" id="email_from" value="<?php echo $this->guerrero->guerrero_real_name; ?>" />
				</fieldset>
				
				<fieldset>
					<label for="email_subject">Subject:</label>
					<input type="text" name="email_subject" id="email_subject" value="¡Únete a mi ejercito de luz y luchemos contra la trata humana!" />
				</fieldset>
				
				<textarea id="the_message" name="email_message">Sé parte de una comunidad virtual de gente comprometida con la lucha contra la trata humana y la explotación infantil. Participa conmigo de un juego interminable en contra de las fuerzas oscuras, reclutando guerreros y promoviendo la lucha con mensajes de luz. 

Ayuda a la organización Fundación Ricky Martin a seguir luchando en contra de la explotación infantil uniéndote a este esfuerzo.

¡Accede a https://guerrerosdeluz.org/ y comienza la lucha! 
				</textarea>
				<input type="image" src="<?php echo base_url('/img/unete_ejercito_btn.png') ?>">
			<?php echo form_close(); ?>
		</div>
		
		<aside id="search_email">
			<form id="search_friend">
				<input id="friend_email" type="email" placeholder="Escribe el email de un amigo...">
				<input id="do_search" type="image" align="top" src="<?php echo base_url('/img/add.png') ?>">
			</form>
			
			<ul id="email_list"></ul>
		</aside>
	</div>
	
</section><!-- end of #reclutamiento_section -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>

<?php if ($this->session->flashdata ('soc_reclutamiento_success') OR $this->session->flashdata ('soc_reclutamiento_error')): ?>
	<script type="text/javascript">
		<?php if ($this->session->flashdata ('soc_reclutamiento_success')): ?>
			alert ('<?php echo $this->session->flashdata ('soc_reclutamiento_success') ?>');
		<?php endif; ?>
		<?php if ($this->session->flashdata ('soc_reclutamiento_error')): ?>
			alert ('<?php echo $this->session->flashdata ('soc_reclutamiento_error') ?>');
		<?php endif; ?>
	</script>
<?php endif; ?>
