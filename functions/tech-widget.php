<?php
	global $tech;
	$tech = get_option('techozoic_options');
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
	$version = $theme_data['Version'];
	
	if(function_exists('register_sidebar')){
	if (isset($tech['column'])){
		if($tech['column'] > 1) {register_sidebar(array('name'=>__('Right Sidebar','techozoic')));}
		if($tech['column'] == 3) {register_sidebar(array('name'=>__('Left Sidebar','techozoic')));}
	} else {
		register_sidebar(array('name'=>__('Right Sidebar','techozoic')));
		register_sidebar(array('name'=>__('Left Sidebar','techozoic')));

	}// End check if tech column set
	
	register_sidebar(array(
		'name'=>__('Footer - Limit 3 Widgets','techozoic'),
		'before_widget' => '<div class="footercont"><ul><li>',
		'after_widget' => '</li></ul></div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
	
	class Techozoic_Nav_Widget extends WP_Widget {

		function Techozoic_Nav_Widget() {
			$widget_ops = array('classname' => 'techozoic_nav', 'description' => __( 'Techozoic Sidebar Navigation Menu - option to add categories to navigation' , 'techozoic') );
			$this->WP_Widget('techozoic_nav', __('Techozoic Navigation' , 'techozoic'), $widget_ops);
		}

		function widget( $args, $instance ) {
			global $tech;
			extract($args);
			$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('Navigation'  , 'techozoic') : $instance['title']);
			$c = $instance['cats'] ? '1' : '0';
			$p = $instance['pages'] ? '1' : '0';
			$s = $instance['separate'] ? '1' : '0';
			echo $before_widget;
			global $post;
			if ( $title)
			echo $before_title . $title . $after_title
?>			<ul id="sidenav">
<?php			if ($s) { 
				echo '<li class="navhead"><h3>'. __('Pages' ,'techozoic').'</h3></li>';
 			} 
			if ($p) { 
				$home_link = get_option('show_on_front');
				if ($home_link == "posts") {?>
					<li class="<?php if (is_home()) echo'current_page_item' ?>"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Home' ,'techozoic')?>"><?php _e('Home' ,'techozoic')?></a></li>
<?php					} else {};
					if (!$tech['nav_exclude_list']){
						wp_list_pages('title_li=');
					} else {
						$nav_exclude = $tech['nav_exclude_list'];
						wp_list_pages("exclude=".$nav_exclude."&title_li=");
					}
				}
			if ($s) { 
?>				<li class="navhead"><h3><?php _e('Categories' ,'techozoic') ?></h3></li>
<?php				} 
			if ($c) { 
				wp_list_categories('title_li='); 
				}
?>			</ul>
<?php	  		echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$new_instance = wp_parse_args( (array) $new_instance, array( 'cats' => 0, 'pages' => 0,'separate' => 0, 'title' => '') );
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['cats'] = $new_instance['cats'] ? 1 : 0;
			$instance['pages'] = $new_instance['pages'] ? 1 : 0;
			$instance['separate'] = $new_instance['separate'] ? 1 : 0;

			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array('cats' => 0, 'pages' => 1,'separate' => 0, 'title' => '') );
			$title = esc_attr( $instance['title'] );
			$cats = $instance['cats'] ? 'checked="checked"' : '';
			$pages = $instance['pages'] ? 'checked="checked"' : '';
			$separate = $instance['separate'] ? 'checked="checked"' : '';
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
			<p>
			<p>
			<input class="checkbox" type="checkbox" <?php echo $pages; ?> id="<?php echo $this->get_field_id('pages'); ?>" name="<?php echo $this->get_field_name('pages'); ?>" /> <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Include Pages in Menu' ,'techozoic')?></label>
			<br />
			<input class="checkbox" type="checkbox" <?php echo $cats; ?> id="<?php echo $this->get_field_id('cats'); ?>" name="<?php echo $this->get_field_name('cats'); ?>" /> <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Include Categories in Menu' ,'techozoic') ?></label>
			<br />
			<input class="checkbox" type="checkbox" <?php echo $separate; ?> id="<?php echo $this->get_field_id('separate'); ?>" name="<?php echo $this->get_field_name('separate'); ?>" /> <label for="<?php echo $this->get_field_id('separate'); ?>"><?php _e('Separate Pages and Categories' ,'techozoic')?></label>
			</p>
<?php
		}
	} //End Class Techozoic_Nav_Widget

	class Techozoic_About_Widget extends WP_Widget {

		function Techozoic_About_Widget() {
			$widget_ops = array('classname' => 'techozoic_about', 'description' => __( 'Author information Widget - Enter short blurb about yourself' ,'techozoic') );
			$this->WP_Widget('techozoic_about', __('Techozoic About' ,'techozoic'), $widget_ops);
		}

		function widget( $args, $instance ) {
			extract($args);
			$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('About Author' ,'techozoic') : $instance['title']);
			$gravatar = $instance['gravatar'] ? '1' : '0';
			$about = $instance['about'];
			if ( !$number = (int) $instance['number'] )
				$number = 50;
			else if ( $number < 25 )
				$number = 25;
			else if ( $number > 100 )
				$number = 100;

			echo $before_widget;
			global $post;
			if ( $title)
			echo $before_title . $title . $after_title;
?>			<span class="alignleft">
<?php			if ($gravatar) { 
				$email = get_bloginfo(admin_email); 
				echo get_avatar( $email, $number ); 
			}
?>			</span><p>
<?php			echo $about;
?>			</p><div style="clear:both"></div>
<?php	  		echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$new_instance = wp_parse_args( (array) $new_instance, array( 'about' => '', 'gravatar' => 0, 'title' => '') );
			$instance['about'] = $new_instance['about'];
			$instance['gravatar'] = $new_instance['gravatar'] ? 1 : 0;
			$instance['number'] = (int) $new_instance['number'];
			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array('about' => '', 'gravatar' => 0) );
			$title = esc_attr( $instance['title'] );
			$about = $instance['about'] ;
			$gravatar = $instance['gravatar'] ? 'checked="checked"' : '';
			$number = isset($instance['number']) ? absint($instance['number']) : 50;
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
			<p>
			<p><label><?php _e('Write About yourself here' ,'techozoic')?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('about'); ?>" name="<?php echo $this->get_field_name('about'); ?>"><?php echo $about; ?></textarea>
			<br />
			<input class="checkbox" type="checkbox" <?php echo $gravatar; ?> id="<?php echo $this->get_field_id('gravatar'); ?>" name="<?php echo $this->get_field_name('gravatar'); ?>" /> <label for="<?php echo $this->get_field_id('gravatar'); ?>"><?php _e('Enable Gravatar' ,'techozoic') ?></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Size of Gravatar' ,'techozoic') ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
			<small><?php _e('Between 25 and 100 Pixels' ,'techozoic') ?></small></p>

<?php
		}
	} //End Class Techozoic_About_Widget

		class Techozoic_Meta_Widget extends WP_Widget {

		function Techozoic_Meta_Widget() {
			$widget_ops = array('classname' => 'techozoic_meta', 'description' => __( 'Meta Information as well as Dashboard and Log In/Out Links' ,'techozoic') );
			$this->WP_Widget('techozoic_meta', __('Techozoic Meta' ,'techozoic'), $widget_ops);
		}

		function widget( $args, $instance ) {
			extract($args);
			$title = apply_filters('widget_title', empty( $instance['title'] ) ? "" : $instance['title']);
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
			else { 
 				echo $before_title . __('Meta','techozoic') . $after_title;		
			}
?>
			<ul><?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional' ,'techozoic') ?>"><?php _e('Valid XHTML' ,'techozoic') ?></a></li>
				<li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.' ,'techozoic') ?>">WordPress</a></li>
<?php 			wp_meta(); 
?>
			</ul>
<?php	  		echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array('title' => '') );
			$title = esc_attr( $instance['title'] );
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>:</label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

<?php
		}
	} //End Class Techozoic_Meta_Widget

		class Techozoic_RSS_Widget extends WP_Widget {

		function Techozoic_RSS_Widget() {
			$widget_ops = array('classname' => 'techozoic_rss', 'description' => __( 'Link to RSS feed for blog as well as comment RSS feed' ,'techozoic') );
			$this->WP_Widget('techozoic_rss', __('Techozoic RSS' ,'techozoic'), $widget_ops);
		}

		function widget( $args, $instance ) {
			extract($args);
			$title = apply_filters('widget_title', empty( $instance['title'] ) ? "" : $instance['title']);
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
			else { 
				echo $before_title . __('Syndicate','techozoic') . $after_title;	
			}
?>
			<ul>
				<li><a href="<?php bloginfo('rss2_url'); ?>">RSS 2.0</a></li>
<?php 		if (is_home() ) { ?>	
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>">RSS 2.0 (<?php _e('Comments' ,'techozoic') ?>)</a></li>
<?php 			}
?>			</ul>
<?php	  		echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array('title' => '') );
			$title = esc_attr( $instance['title'] );
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

<?php
		}
	} //End Class Techozoic_RSS_Widget
	
		//Register Widgets for Sidebars
		register_widget('Techozoic_Nav_Widget');
		register_widget('Techozoic_About_Widget');
		register_widget('Techozoic_Meta_Widget');
		register_widget('Techozoic_RSS_Widget');
	} //End if(function_exists('register_sidebar'))
?>
