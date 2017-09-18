<?php

$payload = $_REQUEST['payload'];

$payload = json_decode($payload);


if ($payload->repository->full_name !== 'wusisu/wusisu.com') {
  return 'not update';
}

if ($payload->ref !== 'refs/heads/master') {
  return 'not update';
}

echo 'update blog';

system('/root/repos/update_blog.sh');
