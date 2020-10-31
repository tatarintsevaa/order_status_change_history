{capture name="mainbox"}

    {include file="common/pagination.tpl"}

    {if $history}
        <div class="table-responsive-wrapper">
            <table class="table table--relative table-responsive">
                <thead>
                <tr>
                    <th>{__("order_id")}</th>
                    <th>{__("old_order_status")}</th>
                    <th>{__("new_order_status")}</th>
                    <th>{__("user")}</th>
                    <th>{__("time")}</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$history item="log"}
                    <tr>
                        <td data-th="{__("order_id")}">{$log.order_id}</td>
                        <td data-th="{__("old_order_status")}">{$log.old_status}</td>
                        <td data-th="{__("new_order_status")}">{$log.new_status}</td>
                        <td data-th="{__("user")}">
                            {if $log.user_id}
                                <a href="{"profiles.update?user_id=`$log.user_id`"|fn_url}">{$log.lastname}{if $log.firstname || $log.lastname}&nbsp;{/if}{$log.firstname}</a>
                            {else}
                                &mdash;
                            {/if}
                        </td>
                        <td data-th="{__("time")}">
                            <span class="nowrap">{$log.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    {else}
        <p class="no-items">{__("no_data")}</p>
    {/if}

    {include file="common/pagination.tpl"}
{/capture}



{include file="common/mainbox.tpl" title=__("order_status_change_history") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar}