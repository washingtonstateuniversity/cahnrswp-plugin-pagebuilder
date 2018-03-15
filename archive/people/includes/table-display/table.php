<style>
	.cahnrswp-people-profiles.table-display.cwp-table {
		display:table;
		width: 100%;
	}
	.cahnrswp-people-profiles.table-display .cwp-table-row {
		display:table-row;
	}
	.cahnrswp-people-profiles.table-display .cwp-table-row:nth-child(odd) {
		background: #f1f1f1;
	}
	.cahnrswp-people-profiles.table-display .cwp-table-cell {
		display:table-cell;
		vertical-align: top;
		padding: 12px 0;
	}
	.cahnrswp-people-profiles.table-display .profile-image{
		width: 180px;
	}
	.cahnrswp-people-profiles.table-display .profile-image img {
		display: block;
		width: 100%;
		height: auto;
	}
	.cahnrswp-people-profiles.table-display .profile-contact {
		padding-left: 1rem;
		width: 220px;
	}
	.cahnrswp-people-profiles.table-display .profile-contact h3 {
		font-size: 1.2rem;
	}
	.cahnrswp-people-profiles.table-display .profile-contact li {
		line-height: normal;
		padding: 0 0 0.3rem 0;
		font-size: 0.9rem;
		max-width: 200px;
	}
	.cahnrswp-people-profiles.table-display .profile-contact ul {
		list-style-type: none;
		margin: 0;
		padding: 0;
	}
	.cahnrswp-people-profiles.table-display .profile-bio {
		font-size: 0.9rem;
	}
	.cahnrswp-people-profiles.table-display .row-header .cwp-table-cell {
		padding-top: 8px;
		padding-bottom: 8px;
	}
	.cahnrswp-people-profiles.table-display .row-header .profile-bio,
	.cahnrswp-people-profiles.table-display .row-header .profile-contact  {
		font-size: 0.9rem;
		font-weight: bold;
		color: #555;
	}
	@media only screen and (max-width: 600px){
		.cahnrswp-people-profiles.table-display .row-header {
			display: none;
		}
		.cahnrswp-people-profiles.table-display.cwp-table {
			display:block;
			width: auto;
		}
		.cahnrswp-people-profiles.table-display .cwp-table-cell {
			display:block;
		}
		.cahnrswp-people-profiles.table-display .cwp-table-cell.profile-image {
			width: 200px;
			float: left;
		}
		.cahnrswp-people-profiles.table-display .cwp-table-cell.profile-contact {
			margin-left: 200px;
		}
		.cahnrswp-people-profiles.table-display .cwp-table-cell.profile-contact:after {
			content: '';
			display: block;
			clear: both;
		}
	}
	@media only screen and (max-width: 450px){
		.cahnrswp-people-profiles.table-display .row-header {
			display: none;
		}
		.cahnrswp-people-profiles.table-display.cwp-table {
			display:block;
			width: auto;
		}
		.cahnrswp-people-profiles.table-display .cwp-table-cell {
			display:block;
		}
		.cahnrswp-people-profiles.table-display .cwp-table-cell.profile-image {
			width: auto;
			float: none;
		}
		.cahnrswp-people-profiles.table-display .cwp-table-cell.profile-contact {
			margin-left: 0;
		}
	}
</style>
<div class="cahnrswp-people-profiles table-display cwp-table">
	<?php echo $header_html ?>
    <?php echo $people_html ?>
</div>