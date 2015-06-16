<div class="row">
	<label for="fileToUpload">Select a File to Upload</label><br />
	<input class="ui button blue" type="file" ng-model-instant id="fileToUpload" multiple onchange="angular.element(this).scope().setFiles(this)" />
</div>
<div id="dropbox" class="dropbox" ng-class="dropClass"><span>{{dropText}}</span></div>
<div ng-show="files.length">
	<div ng-repeat="file in files.slice(0)">
		<span>{{file.webkitRelativePath || file.name}}</span>
		(
		<span ng-switch="file.size > 1024*1024">
			<span ng-switch-when="true">{{file.size / 1024 / 1024 | number:2}} MB</span>
			<span ng-switch-default>{{file.size / 1024 | number:2}} kB</span>
		</span>
		)
	</div>
	<input type="button" ng-click="uploadFile()" value="Upload" />
	<div ng-show="progressVisible">
		<div class="percent">{{progress}}%</div>
		<div class="progress-bar">
			<div class="uploaded" ng-style="{'width': progress+'%'}"></div>
		</div>
	</div>
</div>


<style>
table { border-collapse: collapse; }
.percent {
	position: absolute; width: 300px; height: 14px; z-index: 1; text-align: center; font-size: 0.8em; color: white;
}
.progress-bar {
	width: 300px; height: 14px;
	border-radius: 10px;
	border: 1px solid #CCC;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#6666cc), to(#4b4b95));
	border-image: initial;
}
.uploaded {
	padding: 0;
	height: 14px;
	border-radius: 10px;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#66cc00), to(#4b9500));
	border-image: initial;
}
.dropbox {
	width: 15em;
	height: 3em;
	border: 2px solid #DDD;
	border-radius: 8px;
	background-color: #FEFFEC;
	text-align: center;
	color: #BBB;
	font-size: 2em;
	font-family: Arial, sans-serif;
}
.dropbox span {
	margin-top: 0.9em;
	display: block;
}
.dropbox.not-available {
	background-color: #F88;
}
.dropbox.over {
	background-color: #bfb;
}

</style>