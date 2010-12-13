<?php
	hook( "js_frontend", "prettyJs" );
	hook( "css_frontend", "prettyCss" );
	
	function prettyCss()
	{
		$self= SYSTEM_URL . "plugins/displayers/prettyphoto/css/";
		echo requireCss( $self . "prettyPhoto.css" );
	}
	
	function prettyJs()
	{
		$self= SYSTEM_URL . "plugins/displayers/prettyphoto/js/";

		echo requireJs( "jquery.js", true );
		echo requireJs( $self . "jquery.prettyPhoto.js" );
	}
	
	function prettyphoto( $project, $files, $group )
	{
		global $clerk;
		
		$html= "";
		$slides= "";
		$totalFiles= 0;
		
		/*First Loop, calculate total files before next loop */
		foreach ( $files as $file => $data )
		{
			if ( $data['filegroup'] == $group['num'] )
			{
				$total++;
			}
		}		
		
		/*Original Loop*/
		foreach ( $files as $file => $data )
		{
			if ( $data['filegroup'] == $group['num'] )
			{
				$totalFiles++;
				
				$bigFile		= PROJECTS_URL . $project['slug'] . '/' . $data['file'];				
				
				$thumbFile		= $data['file'];
				if ( PAGE == timeline ) :
					$thumbWidth = 100;
					$thumbHeight = 100;
				else :
					$thumbWidth		= $clerk->getSetting( "projects_filethumbnail", 1 );
					$thumbHeight	= $clerk->getSetting( "projects_filethumbnail", 2 );
				endif;
				$intelliScaling	= $clerk->getSetting( "projects_thumbnailIntelliScaling", 1 );
				$location		= PROJECTS_URL . $project['slug'] . "/";
				$title = $project['title'];
				
				$thumbnail		= dynamicThumbnail( $thumbFile, $location, $thumbWidth, $thumbHeight, $title, $intelliScaling );
				
	
				$projectthumbFile = $project['thumbnail'];
				

				
				
				
				

				
				
				$width			= ( $data['width'] == 0 ) ? "auto" : $data['width'];
				$height			= ( $data['height'] == 0 ) ? "auto" : $data['height'];
				
				$i++;
				$projectThumb= ( $i == 1 ) ? projectThumbnail() : "";
				
				
				$Gallery = ( $total > 1 ) ? 'prettyPhoto[' . $project['id'] . ']' : "prettyPhoto";

				
				list( $thumbWidth, $thumbHeight )= getimagesize( $thumbnail );
				
				switch ( $data['type'] )
				{
					case "image":
							$thumbWidth= ( $thumbWidth == 0 ) ? "auto" : $thumbWidth;
							$thumbHeight= ( $thumbHeight == 0 ) ? "auto" : $thumbHeight;
		
							$thumbID= ( $i == 1 ) ? 'id="thumb' . $project['id'] . '"' : "";

							$slides.= '<div class="project-link" id="file' . $data['id'] . '"><a ' . $thumbID . ' href="' . $bigFile . '" rel="' . $Gallery . '" title="' . $project['text'] . '" class="thumbnail">' . $thumbnail . '</a>';	
							
											
							break;
					case "video":
							$title= ( empty( $data['title'] ) ) ? "Video" : $data['title'];
							/*$mediaThumb= ( empty( $data['thumbnail'] ) ) ? $title : '<img src="' . $thumbnail . '" width="' . $thumbWidth . '" height="' . $thumbHeight . '" />';*/

							
							$slides.= '<div class="project-link" id="file' . $data['id'] . '"><a ' . $thumbID . ' href="' . $bigFile . '?width=' . $width . '&amp;height=' . $height.'" class="thumbnail" rel="' . $Gallery . '" title="' . $project['title'] . '">' . $projectThumb . '</a>';								
							
							break;
					case "audio":
							$title= ( empty( $data['title'] ) ) ? "Audio" : $data['title'];
							$mediaThumb= ( empty( $data['thumbnail'] ) ) ? $title : '<img src="' . $thumbnail . '" width="' . $thumbWidth . '" height="' . $thumbHeight . '" />';
							
							$slides.= '<div class="file" id="file' . $data['id'] . '"><a ' . $thumbID . ' class="popper" href="#" onclick="popper(\''. $data['id'] . '\', \'' . $width . '\', \'' . $height . '\', true);return false;">' . $mediaThumb . '</a><div class="popcontent">' . audioplayer( $data, $project ) . '</div>';
							break;
				}
				
				if ( $clerk->getSetting( "projects_hideFileInfo", 1 ) == false  && ( !empty( $data['title'] ) || !empty( $data['caption'] ) ) )
				{
					$info_html= '<div class="info">
								<span class="title">' . $data['title'] . '</span>
								<span class="caption">' . html_entity_decode( $data['caption'] ) . '</span>';
					
					$info_html= call_anchor( "pop_info", array( 'html' => $info_html, 'file' => $data ) );
					
					$info= $info_html['html'] . '</div>';
				}
				
				$slides.= $info . '</div>';
			}
			
			$info= "";
		}
		
		return $slides;
		
	}
?>
