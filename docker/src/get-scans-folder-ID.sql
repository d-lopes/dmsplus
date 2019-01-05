/* switch to Seed DMS database */
use seeddms;

/* get ID from previously created folder 'scans' */
select id from tblFolders where name = 'scans';
