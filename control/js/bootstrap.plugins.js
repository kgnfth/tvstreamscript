/* ==========================================================
 * bootstrap-placeholder.js v2.0.0
 * http://jasny.github.com/bootstrap/javascript.html#placeholder
 * 
 * Based on work by Daniel Stocks (http://webcloud.se)
 * ==========================================================
 * Copyright 2012 Jasny BV.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

/* TODO: turn this into a proper bootstrap plugin */

$(function () {
		$('*[data-fileupload]').each(function () {
			var container = $(this);
			var input = $(this).find(':file');
			var name = input.attr('name');
			if (input.length == 0) return;
			
			var preview = $(this).find('.fileupload-preview');
			if (preview.css('display') != 'inline' && preview.css('height') != 'none') preview.css('line-height', preview.css('height'));
  
			var remove = $(this).find('*[data-dismiss="fileupload"]');
			
			var hidden_input = $(this).find(':hidden[name="'+name+'"]');
			if (!hidden_input.length) {
				hidden_input = $('<input type="hidden" />');
				container.prepend(hidden_input);
			}
  
			var type = container.attr('data-fileupload') == "image" ? "image" : "file";
			input.attr('name', '');
			
			input.change(function(e) {
				hidden_input.val('');
				hidden_input.attr('name', '')
				input.attr('name', name);
  
				var file = e.target.files[0];
				
				if (type == "image" && preview.length && (typeof file.type !== "undefined" ? file.type.match('image.*') : file.name.match('\\.(gif|png|jpg)$')) && typeof FileReader !== "undefined") {
					var reader = new FileReader();
  
					reader.onload = function(e) {
					   preview.html('<img src="' + e.target.result + '" ' + (preview.css('max-height') != 'none' ? 'style="max-height: ' + preview.css('max-height') + ';"' : '') + ' />');
					   container.addClass('fileupload-exists').removeClass('fileupload-new');
					}
  
					reader.readAsDataURL(file);
				} else {
					preview.html(escape(file.name));
					container.addClass('fileupload-exists').removeClass('fileupload-new');
				}
			});
  
			remove.click(function() {
				hidden_input.val('');
				hidden_input.attr('name', name);
				input.attr('name', '');
  
				preview.html('');
				container.addClass('fileupload-new').removeClass('fileupload-exists');
  
				return false;
			});
		})
});

/* ============================================================
 * bootstrap-rowlink.js v2.0.2
 * http://jasny.github.com/bootstrap/javascript.html#rowlink
 * ============================================================
 * Copyright 2011 Jasny BV, Netherlands.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */

/* TODO: Turn this into a proper jquery plugin */

$(function () {
    $('*[data-rowlink]').each(function () {
        var target = $(this).attr('data-rowlink');
        
        (this.nodeName == 'tr' ? $(this) : $(this).find('tr:has(td)')).each(function() {
            var link = $(this).find(target).first();
            if (!link.length) return;
            
            var href = link.attr('href');

            $(this).find('td').not('.nohref').click(function() {
                window.location = href;
            });

            link.replaceWith(link.html());
        });
    });
});
