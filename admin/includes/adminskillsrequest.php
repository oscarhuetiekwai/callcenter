<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 2012 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
    session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classskills.php');
	require_once('classskill.php');
	
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminskill']) ) {
			if ( $_GET['adminskill'] == 'saveskill' ) {
				$skill = new Skill($_GET['skillid'], $_GET['tenantid'], $_GET['tenant'], $_GET['skill'], $_GET['desc']);
				$skills =  new Skills();
				
				if ( $skills->saveSkill( $skill ) ) {
					echo 'adminskill:saveskill:1:'; 
				} else {
					echo 'adminskill:saveskill:0:' . $skills->getLastErrorMessage(); 
				}
			} else if ( $_GET['adminskill'] == 'skilllist' ) {
				$skills =  new Skills();
				$skilllist = $skills->displaySkillList( $_GET['tenantid'] );
				
				$skilllist = str_replace(":", "<dquote />", $skilllist);
				$skilllist = str_replace("|", "<dpipe />", $skilllist);
				echo 'adminskill:skilllist:' . $skilllist;
			} else if ( $_GET['adminskill'] == 'skillinfo' ) {
				$skills =  new Skills();
				$skill = $skills->getSkillInfo($_GET['skillid']);
				
				$szSkillInfo = $skill->getSkillID() . '|' . $skill->getTenantID() . '|' . $skill->getTenant() . '|' . $skill->getSkill() . '|' . $skill->getSkillDesc();
					
				$szSkillInfo = str_replace(":", "<dquote />", $szSkillInfo);
				$szSkillInfo = str_replace("|", "<dpipe />", $szSkillInfo);
				
				echo 'adminskill:skillinfo:' . $szSkillInfo;
			} else if ( $_GET['adminskill'] == 'skilldelete' ) {
                                $errorSkillsDelete = 'Skill ID Not Deleted: ';
                                $skills = new Skills();

                                if ( isset($_GET['skills']) ) {
                                        $arraySkills = explode( ";", $_GET['skills']);
					foreach ( $arraySkills as $skillid ) {
						if(!$skills->deleteSkill( $skillid ))
                                                {
                                                        $errorSkillsDelete .= $skillid . ',';
                                                }
					}

                                        if($errorSkillsDelete=='Skill ID Not Deleted: ')
                                            echo "adminskill:deleteskill:1:Successfully deleted record(s).";
                                        else
                                            echo "adminskill:deleteskill:0:Deletion error !!!:" . $errorSkillsDelete;
                                }
                        }
		}
	}
?>