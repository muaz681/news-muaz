import Vue from 'vue';

//Vue.prototype.$apiRoot = 'https://api.voicetv24.com/api/';
//Vue.prototype.$rootUrl = 'https://bn.voicetv.tv';

Vue.filter('striphtml', function (value) {
  var div = document.createElement("div");
  div.innerHTML = value;
  var text = div.textContent || div.innerText || "";
  return text;
});

Vue.filter('truncate', function (text, length, clamp) {
  clamp = clamp || '...';
  var node = document.createElement('div');
  node.innerHTML = text;
  var content = node.textContent;
  return content.length > length ? content.slice(0, length) + clamp : content;
});
