<?php
    $urls[] = "http://139.199.7.106:1234/result.json";
    foreach ($urls as $link) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode == 404) {
                $content[] = "";
            } else {
                $content[] = curl_exec($ch);
            }
        } catch (Exception $ex) {
            exit;
        }
    }
?>

  <html>
    
    <head>
      <meta charset='UTF-8'>
      <title>1024幸福家园导航</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="statics/js/vue.js"></script>
      <script src="statics/js/jquery-1.9.1.min.js"></script>
      <script src="statics/js/jquery.lazyload.min.js"></script>
      <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!-- Compiled and minified CSS -->
      <link rel="stylesheet" href="statics/css/materialize.min.css">
      <!-- Compiled and minified JavaScript -->
      <script src="statics/js/materialize.min.js"></script>
      <script>
          init = function() { $('.modal').modal(); $("img.lazy").lazyload(); }
          tenxcloud = function() { return <?php echo $content[0]; ?> };
          change = function(o) { displayList.ssList=o.ssList; displayList.update=o.update; document.getElementById('sstable').sortCol=0; }
          $(function(){
               init();
               $(".button-collapse").sideNav();
               $(".a-nav-bar>li").click(function(){
                  $("#location").text($(this).text())
                  $(this).addClass("active");
                  $(this).siblings().removeClass("active");
              });
          });
          var _hmt = _hmt || [];
          (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?3bd85226480ffe5778d81692158a7c4f";
            var s = document.getElementsByTagName("script")[0]; 
            s.parentNode.insertBefore(hm, s);
          })();
      </script>
      <style> 
          body{display: flex; min-height: 100vh; flex-direction: column;}
          main{flex: 1 0 auto;}
          footer.page-footer{margin-top: 0px;}
          span.badge{position: inherit; white-space:nowrap;margin-left: 0;}
          span.badge.new{float: none;}
          td, th{word-wrap: normal; word-break: break-all;}
          .modal{ max-height: 80%; }
      </style>
    </head>
    <body style="background: url(statics/img/background.jpg) repeat fixed top left">
      <main>
        <div class="container">
<nav class="teal darken-2">
  <div class="nav-wrapper">
    <a href="" class="brand-logo"> ❀<span id="location">广州</span>数据中心❀</a>
    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>

    <ul id="nav" class="right hide-on-med-and-down a-nav-bar">
      <li class="active"><a href="javascript:void(0)" onclick="change(tenxcloud()) , setTimeout(init, 1);">广州</a></li>
    </ul>
      <ul class="side-nav a-nav-bar" id="mobile">
      <li class="active"><a href="javascript:void(0)" onclick="change(tenxcloud()) , setTimeout(init, 1);">广州</a></li>
      </ul>
  </div>
</nav>
          <div id="sslinks" style="background: white;">
            <table id="sstable" class="striped">
             
              <thead class="teal-text text-darken-2">
                <tr>
                  <th onclick="sortTable('sstable',0,'int');" style="cursor: pointer">编号</th>
                  <th onclick="sortTable('sstable',1);" style="cursor: pointer">地址</th>
                  <th onclick="sortTable('sstable',2,'float');" style="cursor: pointer">延迟</th>
                  <th onclick="sortTable('sstable',3,'int');" style="cursor: pointer">下载速度</th>
                  <th onclick="sortTable('sstable',4);" style="cursor: pointer">线路信息</th>
                </tr>
              </thead>
              <tr v-for="(index, item) in ssList" class="grey-text text-darken-2 responsive-table">
                <td>{{ $index+1 }}</td>
                <td>{{ item.addr }}</td>
                <td>{{ item.delay }}s</td>
                <td>{{ item.speed }}KB/s 
<span class="new badge" data-badge-caption="KB/s" v-if="item.speed-item.last_speed>0">▲{{ (item.speed-item.last_speed) }}</span>
<span class="new badge blue-grey" data-badge-caption="KB/s" v-if="item.speed-item.last_speed<0">▼{{ (item.last_speed-item.speed) }}</span>
                </td>
                <td><a href="#ss_{{ $index+1 }}" class="grey-text text-darken-2">{{ item.remark }}</a></td>
              </tr>
            </table>
              <div v-for="(index, item) in ssList" id="ss_{{ $index+1 }}" class="modal">
                <div class="modal-content">
                  <h5>详细信息</h5>
    <div class="row">
	<div class="col m6 s12">
		<p>二维码：</p><p style="text-align: center;"><img class="lazy" data-original="http://pan.baidu.com/share/qrcode?w=150&h=150&url={{ showurl(item) }}" width="150" height="150"></p>
	</div>
	<div class="col m6 s12">
                  <p>原文：</p><code style="display: block; text-align: center; word-wrap: normal; word-break: break-all;">{{ item.url }}</code>
                  <p>译文：</p><code style="display: block; text-align: center; word-wrap: normal; word-break: break-all;">{{ showurl(item) }}</code>
	</div>
    </div>
                <div class="modal-footer">
                  <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                </div>
              </div>
          </div>
        <footer class="page-footer teal darken-2">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">❂链路状况提示</h5>
                <p class="grey-text text-lighten-4">延迟计算策略是TCP三次握手，而非ICMP报文，会比PING测量值大</p>
                <p class="grey-text text-lighten-4">标记“共享”为非私有“自”建通道，标记“限速”为低于8Mpbs通道，未标记代表人懒</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">❁更新时间</h5>
                <p v-model="update" class="grey-text text-lighten-4">{{ update }}</p>
                <p class="grey-text text-lighten-4">更新过程所需时间受数据中心网络影响</p>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            Copyright © 2016 KEVI_ - 数据来源互联网。禁止转发。禁止二次利用。禁止转载至任何网页。
            </div>
          </div>
        </footer>
        </div>
        </div>
        </main>
    </body>
<script>var displayList = new Vue({el: 'body', data: tenxcloud(), methods: { showurl(o) { if (o.encodeurl) return o.encodeurl; var s = o.url; var p = s.lastIndexOf('#'); var r = btoa(unescape(encodeURIComponent(s.substring(p+1)))); var url = s.substring(5, p)+"#"+r; o.encodeurl = "ss://"+btoa(url); return o.encodeurl;}}})</script>
<script>function generateCompareTRs(e,t){return function(r,n){return vValue1=convert(r.cells[e].firstChild.textContent,t),vValue2=convert(n.cells[e].firstChild.textContent,t),vValue2>vValue1?-1:vValue1>vValue2?1:0}}function convert(e,t){switch(t){case"int":return parseInt(e);case"float":return parseFloat(e);case"date":return new Date(Date.parse(e));default:return e.toString()}}function sortTable(e,t,r){for(var n=document.getElementById(e),a=n.tBodies[0],o=a.rows,l=new Array,s=0;s<o.length;s++)l[s]=o[s];n.sortCol==t?l.reverse():l.sort(generateCompareTRs(t,r));for(var u=document.createDocumentFragment(),c=0;c<l.length;c++)u.appendChild(l[c]);a.appendChild(u),n.sortCol=t}</script>
  </html>
