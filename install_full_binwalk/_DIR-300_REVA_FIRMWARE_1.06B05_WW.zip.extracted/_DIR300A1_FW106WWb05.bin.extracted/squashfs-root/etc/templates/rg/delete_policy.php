#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
if (query("/security/policy/enable")==1)
{
	$policy_id = 0;
	for ("/security/policy/entry")
	{
		$policy_id++;
		
		/* flush and delete policy rule and filter */
		echo "iptables -F FOR_POLICY_FILTER".$policy_id."\n";
		echo "iptables -F FOR_POLICY_RULE".$policy_id."\n";
		echo "iptables -X FOR_POLICY_FILTER".$policy_id."\n";
		echo "iptables -X FOR_POLICY_RULE".$policy_id."\n";
	}
}

?>
