!function(a){var i=wp.customize;i.bind("pane-contents-reflowed",function(){var e=[];i.section.each(function(t){"ast_section"===t.params.type&&void 0!==t.params.section&&e.push(t)}),e.sort(i.utils.prioritySort).reverse(),a.each(e,function(t,e){a("#sub-accordion-section-"+e.params.section).children(".section-meta").after(e.headContainer)});var n=[];i.panel.each(function(t){"ast_panel"===t.params.type&&void 0!==t.params.panel&&n.push(t)}),n.sort(i.utils.prioritySort).reverse(),a.each(n,function(t,e){a("#sub-accordion-panel-"+e.params.panel).children(".panel-meta").after(e.headContainer)})});var t=wp.customize.Panel.prototype.embed,s=wp.customize.Panel.prototype.isContextuallyActive,e=wp.customize.Panel.prototype.attachEvents;wp.customize.Panel=wp.customize.Panel.extend({attachEvents:function(){if("ast_panel"===this.params.type&&void 0!==this.params.panel){e.call(this);var n=this;n.expanded.bind(function(t){var e=i.panel(n.params.panel);t?e.contentContainer.addClass("current-panel-parent"):e.contentContainer.removeClass("current-panel-parent")}),n.container.find(".customize-panel-back").off("click keydown").on("click keydown",function(t){i.utils.isKeydownButNotEnterEvent(t)||(t.preventDefault(),n.expanded()&&i.panel(n.params.panel).expand())})}else e.call(this)},embed:function(){if("ast_panel"===this.params.type&&void 0!==this.params.panel){t.call(this);a("#sub-accordion-panel-"+this.params.panel).append(this.headContainer)}else t.call(this)},isContextuallyActive:function(){if("ast_panel"!==this.params.type)return s.call(this);var e=this,n=this._children("panel","section");i.panel.each(function(t){t.params.panel&&t.params.panel===e.id&&n.push(t)}),n.sort(i.utils.prioritySort);var a=0;return _(n).each(function(t){t.active()&&t.isContextuallyActive()&&(a+=1)}),0!==a}});var n=wp.customize.Section.prototype.embed,o=wp.customize.Section.prototype.isContextuallyActive,c=wp.customize.Section.prototype.attachEvents;wp.customize.Section=wp.customize.Section.extend({attachEvents:function(){if("ast_section"===this.params.type&&void 0!==this.params.section){c.call(this);var n=this;n.expanded.bind(function(t){var e=i.section(n.params.section);t?e.contentContainer.addClass("current-section-parent"):e.contentContainer.removeClass("current-section-parent")}),n.container.find(".customize-section-back").off("click keydown").on("click keydown",function(t){i.utils.isKeydownButNotEnterEvent(t)||(t.preventDefault(),n.expanded()&&i.section(n.params.section).expand())})}else c.call(this)},embed:function(){if("ast_section"===this.params.type&&void 0!==this.params.section){n.call(this);a("#sub-accordion-section-"+this.params.section).append(this.headContainer)}else n.call(this)},isContextuallyActive:function(){if("ast_section"!==this.params.type)return o.call(this);var e=this,n=this._children("section","control");i.section.each(function(t){t.params.section&&t.params.section===e.id&&n.push(t)}),n.sort(i.utils.prioritySort);var a=0;return _(n).each(function(t){void 0!==t.isContextuallyActive?t.active()&&t.isContextuallyActive()&&(a+=1):t.active()&&(a+=1)}),0!==a}})}(jQuery);