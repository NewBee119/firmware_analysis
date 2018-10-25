<!-- count remaining number of rules -->
<script>
function remain_rules()
{
    <?
        $count_rules=0;
        $index=0;
        while($index < $MAX_RULES)
        {
            $index++;
            anchor($COUNT_RULES_PATH.$index);
            if(get("h",$COUNT_RULES_VALUE)!="")
            {
                $count_rules++;
            }
        }
        $remain_rules=$MAX_RULES-$count_rules;
    ?>
    document.write("<?=$m_remain_rules?><font color=red><?=$remain_rules?></font>");
}
</script>
