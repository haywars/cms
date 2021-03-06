<?
	$title = 'Add Article';
	$no_sidebar = true;
	template::inc('intranet','top');

	//if the Page is reload.Make sure tags values are empty
	unset($_SESSION['blog_article']);
?>

<form method="post" name="postarticles">
		<!-- Tag Table Info Start -->
	<input type="hidden" name="active" id="active" value="1" />
	<input type="hidden" name="blog_hidden" id="blog_hidden" value="" />
	<input type="hidden" name="blog_keywords" id="blog_keywords" value="" />
	<input type="hidden" name="media_item_ide" id="media_item_ide" value="">
	<input type="hidden" name="type" id="type" value="add" />
	 
		<!-- Tag Table Info Ends -->
		<!-- Article Table Info Start -->
	<input type="hidden" name="author__person_ide" id="author__person_ide" value="<?=$_SESSION['login']['person_ide']?>" />
		
		
	<!-- Primary Div start -->
	<div id="primary">
		
		
		<h1 class="module-bar">
			<?=$title?>
		</h1>
		
		<!-- This will display the progress of the article.-->
		<div>
			<span id="article_progres"></span>
			<div name="validate" id="validate" class="error"></div>
		</div>
		
		
		
		<h1 align="right"><a href ="/admin/blog/article/list">View all articles</a></h1>
		


		 <!-- Section for Title Start -->
		<div class="title">
			Title
			<br />
			<input type="text" name="title" class="title" value="" style="width:630px;" />
		</div>
		 <!-- Section for Title Ends -->


		 <!-- Section for Intro Start -->
		<div id="inro">
			Intro(optional)
			<br />
			<textarea name="introduction" id="introduction"  style="width:630px;;"></textarea>
		</div>
		<!-- Section for Intro Ends -->
		
		
		<!-- Section for Article Body Start -->
		<div id="article">
			Article body
			<br />
			<?
				// include html editor
				 $html_editor = NULL;
				$html_editor['id'] = 'article_content';
				$html_editor['css'] = '/pages/admin/blog/article/html_editor.css';
				$html_editor['width'] = 640;
				$html_editor['height'] = 350;
				$html_editor['innerHTML'] = $_POST['article_content']; // $_POST['article_content'];
				include('modules/html_editor/html_editor.php'); 
				// end include html editor						
			?>
		</div>
		<!-- Section for Article Body Ends -->
		<br />
		
		<!-- Section for Tag Start -->
		<h2 class="module-bar">Tags</h2>
			<div class="module-body">
				 <table width="100%" cellpadding="2" cellspacing="2">
					<tr>
						<td colspan="2">
							<!-- Progress show here -->
								<span id="tag_progres"></span>
							<!-- Progress show here -->
						</td>
					</tr>
					<tr>
						<td width="30%" align="left" valign="top">
							<!-- left Section Start -->
							<table width="100%" cellpadding="2" cellspacing="2">
								<tr valign="top">
									<td nowrap="nowrap" align="left" valign="top">
										<input type="text" name="name" id="name" value="Add new tag" style="width:180px;" onfocus="swap(this)" onblur="swap(this,true)"  onkeydown="if (onEnter(event)) {save_tags(this.form); return false}" />
										<input type="button" name="Add" value="Add" onclick="save_tags(this.form);"/>
									</td>
								</tr>
								<tr valign="top">
									<td align="left" valign="top"><em>Seperate tags with commas</em></td>
								</tr>			
							</table>
							
							<!-- left Section Ends -->
						</td>
						<td width="70%" align="left" valign="top">
							<!-- Right Section Start -->
							<table width="100%" cellpadding="2" cellspacing="2">
								<tr valign="top">
									<td align="left" valign="top"><b>Tags used on this post:</b></td>
								</tr>
								<tr valign="top">
									<td align="left" valign="top">
										 <div id="alltag"></div>
									</td>
								</tr>
							</table>
							<!-- Right Section Ends -->		
						</td>
					</tr>
					 
				
				
				</table> 
			</div>
		 
		<!-- Section for Tag Ends -->
	
		<!-- Section for Image Start -->
		<div>
			<?
				// image picker module
				include('pages/admin/blog/article/images/images.php');
			?>
		</div>
		<!-- Section for Image Ends -->
	
	
	</div>
	
	<!-- Primary Div ends  -->


	<!-- Secondary Div start -->
	<div id="secondary">
	
		<!-- Section for Blogs/Category Start -->
		<h2 class="module-bar">Blogs &amp; Categories</h2>
			<div class="module-body">
            	<div class="module-container">
                    <div id="blog_category_items">
                        <?
                      
                            #fetching all the blog info
                            $aql_blog = "blog {
                                            id,
                                            name 
                                            }
										blog_website {
											where website_id = $website_id
										}
											";
                            $rs_blog = aql::select($aql_blog);
                  
                            foreach ($rs_blog as $blog) { 
                        ?>
                            <div class="blog_title">
                                <label>
                                    <input name="blog_id" class="blog_id" type="radio" onclick="show_blog_category(this.form, '<?=$blog['id']?>', ''); blog_author('<?=$blog['id']?>','<?=$rs_article[0]['author__person_ide']?>');" value="<?=$blog['id']?>"/>
                                    <b>
                                    <?=$blog['name']?>
                                    </b>
                                </label>
                                <div id="blog_category_<?=$blog['id']?>" style="width:80%; padding-left:10px;" ></div>
                            </div>
                      
                        <?
                            } //foreach
                        ?>
                    </div>
        		</div>
			</div>
			 
		 
		<!-- Section for Blogs/Category Ends -->

		<!-- Section for Author Start -->
        
		<h2 class="module-bar">Author</h2>
			<div class="module-body">
	            <div class="module-container" id="blog_authors">
                    <? include('authors.php'); ?>
				</div>
			</div>			 
		
		 <!-- Section for Author Ends -->

<?
	$SQL = "select id from market limit 1";
	$r = $db->Execute($SQL);
	if ( !$db->ErrorMsg() ):
?>
		<!-- Section for Market Start -->
		<h2 class="module-bar">Market</h2>
			<div class="module-body">
	            <div class="module-container">
                    <p>
                    <?
                        #fetching all the market value
                        $aql_market = "market {
                        * 
                        where market.primary = 1          
						order by market.name asc
                        }";
                        
                        $rs_market = aql::select($aql_market);						
                                
                    ?>
                    <select name="market_id">
						<?
							$selected = ($rs_article[0]['market_id'] == "") ? "selected" : "";
						?>
						<option value="NULL" <?=$selected?>>National</option>
                   		<? 		
                        	foreach ($rs_market as $market){ 
							 
                    	?>
                        	<option value="<?=$market['market_id']?>"><?=$market['name']?></option>
                   		<?
                       		} //foreach
                    	?>
                    </select>
                    </p>
				</div>
			</div>
		 <!-- Section for Market Ends -->
<?
	endif;
?>		
		
		<!-- Section for Status Start -->
		<h2 class="module-bar">Status</h2>
		<div class="module-body">
        	<div class="module-container">
				<select name="status">
					<? if (auth('blog_editor')): ?>
                    <option value="A">Published</option>
					<? endif; ?>
					<option value="P">Pending review</option>
					<option value="U">Unpublished</option>
				</select>
				<br />
				<label>
					<input type="checkbox" name="featured" id="featured"  value="1" />
					Featured Article
				</label>	
                
                <br /><br />
                <? popup_date_picker('pub_date', 'pub_date', $_POST['pub_date']); ?>	
                <br />mm/dd/yyyy
                <br /><br />
                
				<script language="javascript" src="/lib/time_picker/mootools.v1.11.js"></script>
                <script language="javascript" src="/lib/time_picker/nogray_time_picker_min.js"></script>
                <script type="text/javascript">
                window.addEvent("domready", function (){
                var tp1 = new TimePicker('time1_picker', 'pub_time', 'time1_toggler',{
                        imagesPath:"/lib/time_picker/time_picker_files/images", 
                        offset:{x:-160, y:-55},
                <?
				if (!$_POST['pub_time'] && $rs_article[0]['post_time']) $_POST['pub_time'] = date('g:ia', strtotime($rs_article[0]['post_time']));
				if (!$_POST['pub_time']) $_POST['pub_time'] = date('g:ia');
				$_POST['pub_time_hr'] = date('G', strtotime($_POST['pub_time']));
				$_POST['pub_time_min'] = date('i', strtotime($_POST['pub_time']));
                ?>
                        selectedTime:{hour:<?=$_POST['pub_time_hr']?>, minute:<?=$_POST['pub_time_min']?>},
                        startTime:{hour:<?=$_POST['pub_time_hr']?>, minute:<?=$_POST['pub_time_min']?>}
                });
                });
                </script>
                <input type="text" name="pub_time" id="pub_time" value="<?=$_POST['pub_time']?>" /> <a href="#" id="time1_toggler"><img src="/images/clock_icon.gif" border="0" align="absmiddle"></a>
                <div id="time1_picker" class="time_picker_div"></div>	 
                
			</div>
		</div>
			 
		<!-- Section for Status Ends -->

		 <!-- Section for Save Start -->
		<h2 class="module-bar">Save &amp; Preview</h2>
		<div class="module-body">
        	<div class="module-container" align="justify">      
				
				<nobr><input type="button" name="Preview" id="Preview" class="Save" value="Preview" onclick="prev_article(this.form);" /></nobr>
				<nobr><input type="button" name="Save" id="Save" class="Save" value="Save" onclick="save_article(this.form);" /></nobr>
				<nobr><input type="button" name="Delete" id="Delete" class="Save" value="Delete" onclick="delete_article(this.form);" /></nobr>
                
			</div>
		</div>
		 <!-- Section for Save Ends -->

		 <!-- Section for SEO Start -->
		<h2 class="module-bar">SEO Keyword Suggestions</h2>
		<div class="module-body">
        	<div class="module-container">
                <span id="keywords1"></span>
                <br />
                <br />
                <p>
                    <input type="text" name="keyword" id="keyword" value="" /><span id="keyword_progres"></span>
                    <input type="button" name="Save" class="Save" value="Add Keyword" onclick="save_keyword(this.form);" />
                </p>
			</div>
        </div>
		 <!-- Section for SEO Ends -->
		 
	</div>
	<!-- Secondary Div Ends -->
    
     
    
</form>

<? 
	 	template::inc('intranet','bottom');
?>