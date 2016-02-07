# Wordpress Fabfile, for migrating the database, and deploying w/ git.
# For background: "My New & Improved Fabfile for Deploying WordPress"
#      http://wp.me/p2a3Vy-1b
#
# This fabfile is specifically-geared for our unique setup, which consists
# of 2 servers (local, remote), and 3 sites (local, dev, and prod). Dev and
# prod sites both live on the remote server.
#
# In general, a little bit of the programming happens on a local site, 
# but the database updates and file uploads (both in Wordpress, and edits 
# to versioned files, via FTP) occur on the remote dev site, and ultimately,
# the dev server is considered to be the "center  of movement", for this fabfile.
# 
#
# Top Commands:
# ----------------------------
# deploy_to_prod
# deploy_to_local
# import_dev_db_to_local
# import_dev_db_to_prod
# dev_server git_status
# dev_server git_commit_all_remote_changes
# prod_server git_commit_all_remote_changes
#
from __future__ import with_statement
from fabric.api import *
import time, random

local_domain = 'oewp.dev'
dev_domain = 'wp.oesite.org.uk'
prod_domain = 'wp.oesite.org.uk'
project_handle = 'master_wp' # used in just a few weird spots, like in naming DB dump files, for example.

# SSH Host String for dev & prod server
env.host_string = 'oesite@dns-systems.net'

# Module-level vars. Don't set these here, set them further below. Consider this as documentation only.
directories = {
    'archive_dir':'/var/www/masters/oewp/temp',   # this is where "temporary" files should be stored (i.e. db dumps that are being migrated)
    'web_dir':'htdocs/',       # this is the deployment directory
}
filenames = {
    'db_dump':'',       # just holds the partial filename
    'db_dump_full':'',  # full path + filename
    'prefix': project_handle + '_', # feel free to change this one if you like
}
db = {
    'host':'local',
    'name':'masters_oewp',
    'user':'root',
    'password':'123',
}

##################################
### Settings - Change These!!! ###
##################################
def prod_server():
	""" Public/Private: sets connection info for the db variable. Generally, this is treated as a private function. """
	db['host'] = ''
	db['name'] = ''
	db['user'] = ''
	db['password'] = ''
	directories['archive_dir'] = '/path/to/archive'
	directories['web_dir'] = 'path/to/prod_site'

def local_server():
	""" Public/Private: sets connection info for the db variable. Generally, this is treated as a private function. """
	db['host'] = 'local'
	db['name'] = 'masters_oewp'
	db['user'] = 'root'
	db['password'] = '123'
	directories['archive_dir'] = '/var/www/masters/oewp/'
	directories['web_dir'] = '/var/www/masters/oewp/public_html'

def dev_server():
	""" Public/Private: sets connection info for the db variable. Generally, this is treated as a private function. """
	db['host'] = ''
	db['name'] = ''
	db['user'] = ''
	db['password'] = ''
	directories['archive_dir'] = '/path/to/archive'
	directories['web_dir'] = 'path/to/dev_site'


######################################################
### Deploy Commands (assumed deploys are from dev) ###
###												   ###
### These are intended to be the primary commands. ###
### Other commands ARE available, like the db dump,###
### fetch, and migrate set below, also the git     ###
###	set gets used pretty frequently also.		   ###
###												   ###
######################################################
def deploy_to_prod():
	""" Public: Primary command. Commits/pushes files in dev, pulls them to prod, imports dev DB to prod.  """
	with settings(warn_only=True): # this allows the git commit & add to fail, like if there's nothing there
		dev_server()
		git_commit_all_remote_changes()	
	prod_server()
	git_pull()
	import_dev_db_to_prod()

def deploy_to_local():
	""" Public: Primary command. Commits/pushes files in dev, pulls to local, imports dev DB to local.  """
	with settings(warn_only=True): # this allows the git commit & add to fail, like if there's nothing there
		dev_server()
		git_commit_all_remote_changes()	
	local_server()
	git_pull()
	import_dev_db_to_local()

def deploy_from_prod_to_dev():
	""" Public: Reverse deploy, from prod to dev.  """
	with settings(warn_only=True): # this allows the git commit & add to fail, like if there's nothing there
		prod_server()
		git_commit_all_remote_changes()	
	dev_server()
	git_pull()
	import_prod_db_to_dev()

def deploy_from_prod_to_local():
	""" Public: Reverse deploy, from prod to local.  """
	with settings(warn_only=True): # this allows the git commit & add to fail, like if there's nothing there
		prod_server()
		git_commit_all_remote_changes()	
	local_server()
	git_pull()
	import_prod_db_to_local()


####################
### Git Commands ###
####################
def git_status():
	""" Public: Navigates to the site directory and executes `git status` """	
	git('status')
	
def git_pull():
	""" Public: Navigates to the site directory and executes `git pull` """	
	git('pull')

def git_push():
	""" Public: Navigates to the site directory and executes `git push` """	
	git('push')

def git_commit():
    """ Public: commits files form server, generic message """
    git('commit -a -m "Commit from dev server"')

def git_add_all():
	""" Public: Navigates to the site directory and executes `git add * --force` """	
	git('add * --force')

def git_commit_all_remote_changes():
    """" Public: Executes git add, commit, and push """
    git_add_all()
    git_commit()
    git_push()


#############################
### Git Command Execution ###
#############################
def git(cmd):
	""" Private: Navigates to the site directory and executes cmd=? param """
	with cd(directories['web_dir']):
		run('git ' + cmd)
	

##################################################
### Database Dump, Fetch, and Migrate Commands ###
##################################################
def import_dev_db_to_local():
	""" Public: Dumps/fetches dev DB, inserts/migrates it to local server. """
	get_dev_db()
	migrate_db_dev_to_local()

def import_dev_db_to_prod():
	""" Public: Dumps dev DB, inserts/migrates it to prod server. """
	dump_dev_db() # for prod, we don't actually need to get the db.
	migrate_db_dev_to_prod()

def import_prod_db_to_dev():
	""" Public: Dumps prod DB, inserts/migrates it to dev server. """
	dump_prod_db() # for prod, we don't actually need to get the db.
	migrate_db_prod_to_dev()

def import_prod_db_to_local():
	""" Public: Dumps prod DB, inserts/migrates it to local server. """
	dump_prod_db() # for prod, we don't actually need to get the db.
	migrate_db_prod_to_local()

def get_dev_db():
    """ Private: Dumps and Fetches the dev DB. Not intended strictly as a fab command. """
    dump_dev_db()
    fetch_dev_db()

def dump_dev_db():
	""" Public: Dumps dev DB. """
	dev_server()
	# set up the filename of the dump:
	filenames['db_dump'] = 'dev_' + filenames['prefix'] + 'dump_' + str(time.time()) + '.sql'
	filenames['db_dump_full'] =  directories['archive_dir'] + '/' + filenames['db_dump']
	# execute the dump
	run(migrate_db_make_dump_db_command())

def dump_prod_db():
	""" Public: Dumps prod DB. """
	prod_server()
	# set up the filename of the dump:
	filenames['db_dump'] = 'prod_' + filenames['prefix'] + 'dump_' + str(time.time()) + '.sql'
	filenames['db_dump_full'] =  directories['archive_dir'] + '/' + filenames['db_dump']
	# execute the dump
	run(migrate_db_make_dump_db_command())

# note: currently, this DB dump is being downloaded to this here directory. Improving that is a TODO item.
def fetch_dev_db():
    """ Private: Fetches dev DB, to this folder. """
    get(filenames['db_dump_full'], filenames['db_dump'])

	
#######################################
### Internal Migration Command Sets ###
#######################################
def migrate_db_dev_to_local():
	""" Private: Inserts the database dump, and updates domain references in the database """
	local_server()
	local(migrate_db_make_insert_dump_command(filenames['db_dump']))
	for c in migrate_db_make_update_commands(dev_domain,local_domain): local(c)

def migrate_db_dev_to_prod():
	""" Private: Inserts the database dump, and updates domain references in the database """
	prod_server()
	run(migrate_db_make_insert_dump_command(filenames['db_dump_full']))
	for c in migrate_db_make_update_commands(dev_domain,prod_domain): run(c)

def migrate_db_prod_to_dev():
	""" Private: Inserts the database dump, and updates domain references in the database """
	dev_server()
	run(migrate_db_make_insert_dump_command(filenames['db_dump_full']))
	for c in migrate_db_make_update_commands(prod_domain,dev_domain): run(c)

def migrate_db_prod_to_local():
	""" Private: Inserts the database dump, and updates domain references in the database """
	local_server()
	local(migrate_db_make_insert_dump_command(filenames['db_dump']))
	for c in migrate_db_make_update_commands(prod_domain,local_domain): local(c)


###############################################
### Commands That Build Bash/MySQL Commands ###
###############################################
def migrate_db_make_update_commands(old_server, new_server):
	""" Private: Makes the Bash + MySQL commands to move the Wordpress database from one domain to another """
	commands = [] # shell commands, just FYI. these are to-be returned.
	update_commands = [
		'UPDATE wp_options SET option_value = replace(option_value, "'+old_server+'","'+new_server+'");',
		'UPDATE wp_posts SET guid = replace(guid,"'+old_server+'","'+new_server+'");',
		'UPDATE wp_postmeta SET meta_value = replace(meta_value,"'+old_server+'","'+new_server+'");',
		'UPDATE wp_posts SET post_content = replace(post_content,"'+old_server+'","'+new_server+'");',
		# example for future additions as needed:
		# 'UPDATE some_table SET some_column = replace(some_column, "find_term", "replace_term");',
	]
	# compile those database commands into shell commands:
	for c in update_commands:
		commands.append('mysql -u %s -p%s -h %s %s -e \'%s\'' % (db['user'], db['password'], db['host'], db['name'], c))
	return commands

def migrate_db_make_insert_dump_command(db_dump_fn):
    """ Private: Makes the Bash + MySQL command to insert the contents of a dump file into a database. """
    # note: currently, this db dump is being downloaded to this here directory. Improving that is still a TODO item.
    command = 'mysql -u %s -p%s -h %s %s < %s' % (db['user'], db['password'], db['host'], db['name'], db_dump_fn)
    return command

def migrate_db_make_dump_db_command():
    """ Private: Makes the Bash + MySQL (admin) command to dump the database. """
    command = 'mysqldump -u %s -p%s -h %s %s > %s' % (db['user'], db['password'], db['host'], db['name'], filenames['db_dump_full'])
    return command