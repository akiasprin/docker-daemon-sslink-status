<?php
    $urls[] = "http://gz-sslink-status-tencent.e7657b22.svc.dockerapp.io/result.json";
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
      <title>❂影梭链路状态查看器❂</title>
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
              $(".a-nav-bar>li").click(function(){
                  $("#location").text($(this).text())
                  $(this).addClass("active");
                  $(this).siblings().removeClass("active");
              });
          });
          $(document).ready(function(){
               init();
               $(".button-collapse").sideNav();
          }) 
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
</nav>
    <body style="background: url(statics/img/background.jpg) repeat fixed top left">
      <main>
        <div class="container">
<nav class="teal darken-2">
  <div class="nav-wrapper">
    <a href="" class="brand-logo"> ❀<span id="location">广州</span>数据中心❀</a>
    <a href="#" data-activates="mobile" class="button-collapse"><i class="material-icons">菜单</i></a>

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
                <td><a href="#modal_{{ $index+1 }}" class="grey-text text-darken-2">{{ item.remark }}</a></td>
              </tr>
            </table>
              <div v-for="(index, item) in ssList" id="modal_{{ $index+1 }}" class="modal">
                <div class="modal-content">
                  <h5>详细信息</h5>
                  <p style="text-align: center;"><img class="lazy" data-original="http://pan.baidu.com/share/qrcode?w=150&h=150&url={{ encodeURIComponent(item.url) }}" width="150" height="150"></p>
                  <code style="display: block; text-align: center; word-wrap: normal; word-break: break-all;">{{ item.url }}</code>
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
                <p class="grey-text text-lighten-4">某些地区日本Vultr、GMO、樱花旗下线路白天受GFW限速</p>
                <p class="grey-text text-lighten-4">标记“共享”为非私有自建通道，标记“限速”为低于8Mpbs通道，未标记仅代表为懒得标记</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">❁更新时间</h5>
                <p v-model="update" class="grey-text text-lighten-4">{{ update }}</p>
                <p class="grey-text text-lighten-4">更新过程所需时间受当地网络影响</p>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            Copyright © 2016 KEVI_ - 禁止转发。禁止二次利用。禁止转载至任何网页。
            </div>
          </div>
        </footer>
        </div>
         <script>
             var displayList = new Vue({
                el: 'body',
                data: tenxcloud()
             })
         </script>

         <script>
/**
 * 比较函数生成器
 * 
 * @param iCol
 *            数据行数
 * @param sDataType
 *            该行的数据类型
 * @return
 */
function generateCompareTRs(iCol, sDataType) {
    return function compareTRs(oTR1, oTR2) {
        vValue1 = convert(oTR1.cells[iCol].firstChild.textContent, sDataType);
        vValue2 = convert(oTR2.cells[iCol].firstChild.textContent, sDataType);
        if (vValue1 < vValue2) {
            return - 1;
        } else if (vValue1 > vValue2) {
            return 1;
        } else {
            return 0;
        }
    };
}

/**
 * 处理排序的字段类型
 * 
 * @param sValue
 *            字段值 默认为字符类型即比较ASCII码
 * @param sDataType
 *            字段类型 对于date只支持格式为mm/dd/yyyy或mmmm dd,yyyy(January 12,2004)
 * @return
 */
function convert(sValue, sDataType) {
    switch (sDataType) {
    case "int":
        return parseInt(sValue);
    case "float":
        return parseFloat(sValue);
    case "date":
        return new Date(Date.parse(sValue));
    default:
        return sValue.toString();
    }
}

/**
 * 通过表头对表列进行排序
 * 
 * @param sTableID
 *            要处理的表ID<table id=''>
 * @param iCol
 *            字段列id eg: 0 1 2 3 ...
 * @param sDataType
 *            该字段数据类型 int,float,date 默认情况下当字符串处理
 */
function sortTable(sTableID, iCol, sDataType) {
    var oTable = document.getElementById(sTableID);
    var oTBody = oTable.tBodies[0];
    var colDataRows = oTBody.rows;
    var aTRs = new Array;
    for (var i = 0; i < colDataRows.length; i++) {
        aTRs[i] = colDataRows[i];
    }
    if (oTable.sortCol == iCol) {
        aTRs.reverse();
    } else {
        aTRs.sort(generateCompareTRs(iCol, sDataType));
    }
    var oFragment = document.createDocumentFragment();
    for (var j = 0; j < aTRs.length; j++) {
        oFragment.appendChild(aTRs[j]);
    }
    oTBody.appendChild(oFragment);
    oTable.sortCol = iCol;
}
         </script>
    </body>
  
  </html>