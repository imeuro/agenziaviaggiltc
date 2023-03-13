<?php 
/**
 * Theme Footer Section for our theme.
 * 
 * Displays all of the footer section and closing of the #main div.
 *
 * @package ThemeGrill
 * @subpackage Accelerate
 * @since Accelerate 1.0
 */
?>

		</div><!-- .inner-wrap -->
	</div><!-- #main -->	
	<?php do_action( 'accelerate_before_footer' ); ?>
		<footer id="colophon" class="clearfix">	
			<?php get_sidebar( 'footer' ); ?>	
			<div class="footer-socket-wrapper clearfix">
				<div class="inner-wrap">
					<div class="footer-socket-area">
						<div class="copyright">LANGUAGE TRAVEL & CONSULTANCY SAS - TEL: 02.33002117</div><br/>
						<div class="copyright"><a href="https://www.rna.gov.it/sites/PortaleRNA/it_IT/home">La società ha ricevuto, nel corso dell’anno precedente, aiuti di stato pubblicati sul Registro Nazionale degli Aiuti - sezione Trasparenza</a></div>										<nav class="footer-menu" class="clearfix">
							<?php
								if ( has_nav_menu( 'footer' ) ) {									
										wp_nav_menu( array( 'theme_location' => 'footer',
																 'depth'           => -1
																 ) );
								}
							?>
		    			</nav>
					</div>
				</div>
			</div>			
		</footer>
		<a href="#masthead" id="scroll-up"><i class="fa fa-long-arrow-up"></i></a>	
	</div><!-- #page -->
	<?php wp_footer(); ?>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5EE2D8RYGB"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-78561161-1');
  gtag('config', 'G-5EE2D8RYGB');
</script>
</body>
</html>