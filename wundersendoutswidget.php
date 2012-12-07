<?php

class wundersendoutsWidget extends WP_Widget {

function register_widget(){
	register_widget( 'wundersendoutsWidget' );
}

/** constructor */
	function wundersendoutsWidget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sendouts', 'description' => 'Display Sendouts Jobs Listings' );
   
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sendouts-widget' );

		/* Create the widget. */
		 parent::WP_Widget(false, $name = 'Sendouts Job Listings', $widget_ops);	
	}

/**Display the widget on the screen.*/
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$limit = $instance['shownum'];
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( !empty($title ))
		echo $before_title . $title . $after_title;
		$jobs = wunderSendOuts::getListings();
	  ?>
	  <ul>
	  <?php
      for($i=0; $i<$limit; $i++){
		?>
		<li class='job-title'><a href='jobs/?job_id=<?= $jobs[$i]->Job_Order_Number;?>'><?= $jobs[$i]->Title;?></a>
		<div><?= date('m/d/Y', strtotime($jobs[$i]->Start_DT));?> - <?php if($jobs[$i]->Job_City){ echo $jobs[$i]->Job_City.", ";}?><?= $jobs[$i]->Job_State;?></div></li>
     <?php }
		?>
		<div><a href='jobs' class='all-jobs'>Search All Positions <i class='icon-arrow-right'></i></a></div>
		</ul>
		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

/**Update the widget settings.*/
	function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => 'Job Listings',
			'shownum' => '5',
			'category' => '39'
			);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			<label for="<?php echo $this->get_field_id( 'shownum' ); ?>"># Listings to Show</label>
			<input id="<?php echo $this->get_field_id( 'shownum' ); ?>" name="<?php echo $this->get_field_name( 'shownum' ); ?>" value="<?php echo $instance['shownum']; ?>" style="width:100%;" />
		</p>


	<?php
	}
}

?>
