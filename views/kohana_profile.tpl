{Kohana_profile}

<script type="text/javascript">{literal}
function kohana_toggle_profile() {
  state = document.getElementById('kohana_profile').style.display;
  if ( state=='none' ) {
    document.getElementById('kohana_profile').style.display = 'block';
    document.getElementById('kohana_profile_toggle').innerHTML = 'Hide Profile';
  } else {
    document.getElementById('kohana_profile').style.display = 'none';
    document.getElementById('kohana_profile_toggle').innerHTML = 'Show Profile';
  }
}
</script>{/literal}

<div id="kohana_profile_toggle" onclick="javascript:kohana_toggle_profile()">Show Profile</div>

<div id="kohana_profile" style="display: none">
<table style="font-size: 80%; line-height: 1em;">
  <tr>
    <th colspan="2">&nbsp;</th>
    <th>&nbsp;</th>
    <th colspan="4">Time (microseconds)</th>
    <th colspan="4">Memory (kB)</th>
  </tr>
  <tr>
    <th class="al">Group</th>
    <th class="al">Name</th>
    <th>Count</th>
    <th>Total</th>
    <th>Min</th>
    <th>Max</th>
    <th>Mean</th>
    <th>Total</th>
    <th>Min</th>
    <th>Max</th>
    <th>Mean</th>
  </tr>
{foreach from=$Kohana_profile item='row'}
  <tr>
  {if $row.name=='Application timings'}
    <td colspan="2" class="al">Application timings</td>
    <td>{$row.count}</td>
    <td>{$row.total_time|sig_number_format:1}s</td>
    <td>{$row.min_time*1000|sig_number_format:2}</td>
    <td>{$row.max_time*1000|sig_number_format:2}</td>
    <td>{$row.average_time*1000|sig_number_format:2}</td>
    <td>&nbsp;</td>
    <td>{$row.min_memory/1000|number_format}</td>
    <td>{$row.max_memory/1000|number_format}</td>
    <td>{$row.average_memory/1000|number_format}</td>
  {else}
    <td class="al">{$row.group_name}</td>
    <td class="al">{$row.name}</td>
    <td>{$row.count}</td>
    <td>{$row.total_time*1000|sig_number_format:2}</td>
    <td>{$row.min_time*1000|sig_number_format:2}</td>
    <td>{$row.max_time*1000|sig_number_format:2}</td>
    <td>{$row.average_time*1000|sig_number_format:2}</td>
    <td>{$row.total_memory/1000|number_format}</td>
    <td>{$row.min_memory/1000|number_format}</td>
    <td>{$row.max_memory/1000|number_format}</td>
    <td>{$row.average_memory/1000|number_format}</td>
  {/if}
  </tr>
{/foreach}
</table>
</div>
