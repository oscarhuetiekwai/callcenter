<script language="JavaScript">
	$(document).ready(function()
	{
		$("#start_date, #end_date").datepick();
	}
	);
</script>
<div class="formtitle">Agent Login / Logout Report</div>
				    <div>&nbsp;</div>
					<div>&nbsp;</div>
				    <!-- Virtual Queue Report -->
					<div id="virtualqueuereport">
						<!-- column 1 -->
					    <div class="col1">
							<div class="formfields">
								<div class="formlabelalign">Start Date:</div>
								     <input type="text" name="start_date" id="start_date" class="formtext" />
						    </div>
							<br />
							<br />
							<div class="formfields">
								<div class="formlabelalign">Select Queue:</div>
								     <select name="queue" id="queue" />
									     <option value="">Queue 1</option>
									 </select>
						    </div>
							<div>&nbsp;</div>
					        <div>&nbsp;</div>
							<div class="formtitlesub">Report Parameters:</div>
							<br />
							<div class="formfields">
								<div class="formlabelalign">Service Level:</div>
								     <input type="text" name="service_level" id="service_level" class="formtextno" />&nbsp;&nbsp;<label class="extwords">secs</label>
						    </div>
							<br />
							<br />
							<div class="formfields">
								<div class="formlabelalign">Abandon Rate:</div>
								     <input type="text" name="abandon_rate" id="abandon_rate" class="formtextno" />&nbsp;&nbsp;<label class="extwords">secs</label>
						    </div>
					    </div>
						<!-- end of column 1 -->
						<!-- column 2 -->
						<div class="col2">
							<div class="formfields">
								<div class="formlabelalign">End Date:</div>
								     <input type="text" name="end_date" id="end_date" class="formtext" />
						    </div>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="btn_radiogroup" for="format">
								<input type="radio" name="format" id="table_format" value="tf" checked />
								<label class="extwords">Table Format</label>
								<div>&nbsp;</div>  
								<div class="formfields">
								<div class="formlabelalign">&nbsp;</div>
								     &nbsp;
						    	</div>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="format" id="graphical_format" value="gf" />
								<label class="extwords">Graphical Format</label>
						    </label>
							<br />
							<br />
							<div>&nbsp;</div>
					        <div>&nbsp;</div>
							<div>&nbsp;</div>
							<div class="formfieldsmemo">
								<div class="formlabelalign">Service Level Calculation:</div>
								     <input type="text" name="total_calls_answered" id="total_calls_answered" class="formtextno" />&nbsp;&nbsp;<label class="extwords">Total Calls Answered within (X) secs / Total Calls Answered</label>
						    </div>
							<br />
							<br />
							<div class="formfieldsmemo">
								<div class="formlabelalign">&nbsp;</div>
								     <input type="text" name="total_calls_received" id="total_calls_received" class="formtextno" />&nbsp;&nbsp;<label class="extwords">Total Calls Answered within (X) secs / Total Calls Received</label>
						    </div>
						</div>
						<!-- end of column 2 -->
					</div>
					<!-- end of Virtual Queue Report -->
    	     </div>