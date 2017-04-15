<script src="https://www.gourdata.com/theme/javascript/layer/layer.js"></script>
<!-- 引入chart.js JS图表库,使用版本为1.1.1,使用bootcss.com的CDN节点 -->
<script type="text/javascript" src="https://cdn.bootcss.com/Chart.js/1.1.1/Chart.min.js"></script>
<div style="font-size:12px;text-align:center">
  <div style="position: relative; overflow: auto; text-align: right; margin:-10px 0 15px 0; font-size: 10px; color: #999;">* 刷新页面可以获取最新的数据，但并非必要的情况下请勿频繁刷新</div>
  <script>jQuery(document).ready(function($) {
      $("a[name='qrcode']").on('click',
      function() {
        str = $(this).attr('data-qrcode');
        str = 'ss://' + str;
        layer.open({
          type: 1,
          title: $(this).attr('data-title'),
          shade: [0.8, '#000'],
          skin: 'layui-layer-demo',
          closeBtn: 1,
          shift: 2,
          shadeClose: true,
          content: '<img style="width: 100%; height: 100%;" src="https://www.gourdata.com/qr?' + str + '"/><div style="position: relative; overflow: auto; text-align: center; margin-bottom: 10px; font-size: 12px;">请使用 Shadowsocks 客户端进行扫描</div>'
        });
      });
    });</script>
    <script type="text/javascript">
        if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
        	if(window.location.href.indexOf("?mobile")<0){
        		try{
        			if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
        				//隐藏一个图片
                var ratioEle = document.getElementById('ratio-div');
                ratioEle.parentNode.removeChild(ratioEle);
        			}else if(/iPad/i.test(navigator.userAgent)){
        			}else{
                //不对Pad进行操作
        			}
        		}catch(e){}
        	}
        }
    </script>
  <table style="width:100%;border:1px solid #e9e9e9;border-bottom:0;border-collapse:separate;border-spacing:0;border-radius:5px;color:#999;font-size:12px;margin-bottom:5px;">
    <!-- START OF CHART.JS -->
    <thead >
      <tr>
        <td colspan="2" style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9;">
          <div style="width:360px;overflow:auto">
            <canvas id="usage" style="float:left"></canvas>
          </div>
            <script type="text/javascript">
              //这里开始获取剩余流量以及已用流量,并生成图表
              var usage = ({$traffic} - {$traffic_free});
              var data = [
              	{
              		value: usage,
              		color:"#F7464A",
                  label:"已用流量"
              	},
              	{
              		value : {$traffic_free},
              		color : "#E2EAE9",
                  label:"剩余流量"
              	}]
                var ctx = document.getElementById("usage").getContext("2d");
                var myDoughnut = new Chart(ctx).Doughnut(data,{
                  responsive: true,
                });
            </script>
        </td>
        <td colspan="2" style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">
          <div id="ratio-div" style="width:360px;overflow:auto">
            <canvas id="ratio" style="float:right"></canvas>
          </div>
            <script type="text/javascript">
            //这里开始获取计算流量比,并生成图表
            var up = ({$traffic_upload}/({$traffic_upload}+{$traffic_download}))*100;
            var dl = ({$traffic_download}/({$traffic_upload}+{$traffic_download}))*100;
            var data = [
              {
                value: up,
                color:"#F38630",
                label:"上行流量(%)"
              },
              {
                value : dl,
                color : "#E0E4CC",
                label:"下行流量(%)"
              }]
              var ctx = document.getElementById("ratio").getContext("2d");
              var myDoughnut = new Chart(ctx).Pie(data,{
                responsive: true,
              });
          </script>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center;padding:10px 15px;border-right:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9">剩余流量/已用流量&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td colspan="2" style="text-align:center;padding:10px 15px;border-right:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9">上行/下行流量比&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
    </thead>
    <!-- END OF CHART.JS -->
    <tbody style="clear:both">
      <tr>
        <td style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">端口</td>
        <td style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">已用上传流量</td>
        <td style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">已用下载流量</td></tr>
      <tr>
        <td style="text-align:center;padding:10px 15px;border-right:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9">{$port}</td>
        <td style="text-align:center;padding:10px 15px;border-right:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9">{$traffic_upload}
          <span style="color:#BBB;">Byte (B)</span> | {$tf_Ul}<span style="color:#BBB;">(MB)</span></td>
        <td style="text-align:center;padding:10px 15px;border-bottom:1px solid #e9e9e9">{$traffic_download}
          <span style="color:#BBB;">Byte (B)</span> | {$tf_Dl}<span style="color:#BBB;">(MB)</span></td>
      </tr>
    </tbody>
  </table>
  <table style="width:100%;border:1px solid #e9e9e9;border-bottom:0;border-collapse:separate;border-spacing:0;border-radius:5px;color:#999;font-size:12px;margin-bottom:5px;overflow:auto;">
    <tbody>
      <tr>
        <td style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">每月流量</td>
        <td style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">剩余流量</td>
        <td style="text-align:center;padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">最后连接</td></tr>
      <tr>
        <td style="text-align:center;padding:10px 15px;border-right:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9">{$traffic}
          <span style="color:#BBB;">Megabyte (MB)</span></td>
        <td style="text-align:center;padding:10px 15px;border-right:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9">{$traffic_free}
          <span style="color:#BBB;">Megabyte (MB)</span></td>
        <td style="text-align:center;padding:10px 15px;border-bottom:1px solid #e9e9e9">{$last_year}
          <span style="color:#BBB;">年</span>{$last_month}
          <span style="color:#BBB;">月</span>{$last_day}
          <span style="color:#BBB;">日</span>,
          <span style="color:#BBB;">{$times}</span>{$last_time}
          <span style="color:#BBB;">分</span></td>
      </tr>
    </tbody>
  </table>
  <table style="width:100%;border:1px solid #e9e9e9;border-bottom:0;border-collapse:separate;border-spacing:0;border-radius:5px;color:#999">
    <tbody>
      <tr>
        <td style="padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">物理地域</td>
        <td style="padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">别名地址</td>
        <td style="padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">混淆方式</td>
        <td style="padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">加密方式</td>
        <td style="padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">扫一扫
          <span style="color:red">( New )</span></td>
      </tr>{$node_list}</tbody>
      <tr>
        <td style="padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">单端口多用户
          <span style="color:red">( New )</span></td>
        <td colspan="4" style="padding:8px 10px;background-color:#fcfcfc;border-bottom:1px solid #e9e9e9">
            单端口多用户节点的物理区域后会标注/单:端口号.例如:日本/单:8080 且混淆方式不能错误,暂时无法扫码连接!
        </td>
      </tr>
  </table>
  <p style="color:#999;border-color:#E9E9E9;padding:10px;border-radius:4px;margin:5px 0;border:1px solid #eee;font-size:12px">注意：由于服务器IP可能发生变化，我们建议您使用别名连接，若您当地DNS干扰严重，请工单联系客服获取真实IP。</p></div>
