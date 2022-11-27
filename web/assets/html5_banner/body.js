(function (lib, img, cjs, ss, an) {

var p; // shortcut to reference prototypes
lib.webFontTxtInst = {}; 
var loadedTypekitCount = 0;
var loadedGoogleCount = 0;
var gFontsUpdateCacheList = [];
var tFontsUpdateCacheList = [];
lib.ssMetadata = [];



lib.updateListCache = function (cacheList) {		
	for(var i = 0; i < cacheList.length; i++) {		
		if(cacheList[i].cacheCanvas)		
			cacheList[i].updateCache();		
	}		
};		

lib.addElementsToCache = function (textInst, cacheList) {		
	var cur = textInst;		
	while(cur != null && cur != exportRoot) {		
		if(cacheList.indexOf(cur) != -1)		
			break;		
		cur = cur.parent;		
	}		
	if(cur != exportRoot) {		
		var cur2 = textInst;		
		var index = cacheList.indexOf(cur);		
		while(cur2 != null && cur2 != cur) {		
			cacheList.splice(index, 0, cur2);		
			cur2 = cur2.parent;		
			index++;		
		}		
	}		
	else {		
		cur = textInst;		
		while(cur != null && cur != exportRoot) {		
			cacheList.push(cur);		
			cur = cur.parent;		
		}		
	}		
};		

lib.gfontAvailable = function(family, totalGoogleCount) {		
	lib.properties.webfonts[family] = true;		
	var txtInst = lib.webFontTxtInst && lib.webFontTxtInst[family] || [];		
	for(var f = 0; f < txtInst.length; ++f)		
		lib.addElementsToCache(txtInst[f], gFontsUpdateCacheList);		

	loadedGoogleCount++;		
	if(loadedGoogleCount == totalGoogleCount) {		
		lib.updateListCache(gFontsUpdateCacheList);		
	}		
};		

lib.tfontAvailable = function(family, totalTypekitCount) {		
	lib.properties.webfonts[family] = true;		
	var txtInst = lib.webFontTxtInst && lib.webFontTxtInst[family] || [];		
	for(var f = 0; f < txtInst.length; ++f)		
		lib.addElementsToCache(txtInst[f], tFontsUpdateCacheList);		

	loadedTypekitCount++;		
	if(loadedTypekitCount == totalTypekitCount) {		
		lib.updateListCache(tFontsUpdateCacheList);		
	}		
};
// symbols:



(lib.bike1 = function() {
	this.initialize(img.bike1);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,348,218);


(lib.car = function() {
	this.initialize(img.car);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,444,186);


(lib.car_wh_left = function() {
	this.initialize(img.car_wh_left);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,56,56);


(lib.car_wh_right = function() {
	this.initialize(img.car_wh_right);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,54,54);


(lib.pic1 = function() {
	this.initialize(img.pic1);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,559,192);


(lib.wheel_left = function() {
	this.initialize(img.wheel_left);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,139,139);


(lib.wheel_right = function() {
	this.initialize(img.wheel_right);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,141,141);// helper functions:

function mc_symbol_clone() {
	var clone = this._cloneProps(new this.constructor(this.mode, this.startPosition, this.loop));
	clone.gotoAndStop(this.currentFrame);
	clone.paused = this.paused;
	clone.framerate = this.framerate;
	return clone;
}

function getMCSymbolPrototype(symbol, nominalBounds, frameBounds) {
	var prototype = cjs.extend(symbol, cjs.MovieClip);
	prototype.clone = mc_symbol_clone;
	prototype.nominalBounds = nominalBounds;
	prototype.frameBounds = frameBounds;
	return prototype;
	}


(lib.Symbol20 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("AvrCFIAAjMIAVAAIAEATQAHgKALgGQALgFAOgBQAbAAAQAWQAPAWAAAjIAAACQAAAhgPATQgQAUgbAAQgNAAgKgEQgLgGgHgJIAABJgAvDgvQgIAGgFAJIAABHQAFAIAIAFQAJAGALAAQAUgBAKgOQAJgNAAgXIAAgCQAAgYgJgRQgLgQgTAAQgMAAgIAFgAhyB4IAAgsIhmAAIAAAsIgYAAIgDhBIAMAAQAMgOAFgOQAHgOABgcIADg4IBgAAIAAB+IAUAAIgDBBgAizgPQgBAXgFARQgHASgIAMIBCAAIAAhmIgsAAgANvA6QgRgVgBggIAAgFQABggARgVQAQgUAggBQAaABARAPQAQAPAAAWIAAABIgZAAQAAgNgJgKQgKgKgPAAQgUAAgKAPQgJAQABAWIAAAFQgBAXAJAOQAKAPAUABQAOgBAKgHQALgJgBgMIAZAAIAAABQABATgSAPQgSAPgYAAQggAAgQgVgALcA7QgSgWAAggIAAgFQgBggAUgVQASgUAagBQAeABAPARQAPASAAAeIAAAQIhfAAIgBABQABAVAKAOQAKAOATAAQANgBALgDQALgEAHgHIALARQgIAIgNAGQgNAFgTAAQgfAAgSgUgALygpQgKALgCASIAAAAIBEAAIAAgDQABgRgJgKQgJgLgQAAQgNAAgKAMgAltA7QgSgWAAggIAAgFQgBggAUgVQASgUAagBQAeABAPARQAPASAAAeIAAAQIhfAAIgBABQABAVAKAOQAKAOATAAQANgBALgDQALgEAHgHIALARQgJAIgMAGQgNAFgTAAQgfAAgSgUgAlYgpQgJALgCASIAAAAIBEAAIAAgDQABgRgJgKQgJgLgQAAQgNAAgLAMgAoNA6QgRgVAAghIAAgEQAAgfARgWQASgUAegBQAfABASAUQARAWABAfIAAAEQgBAhgRAVQgSAVgfAAQgeAAgSgVgAn6glQgKAQAAAVIAAAEQAAAXAKAPQAKAPATABQAUgBAKgPQAKgPAAgXIAAgEQAAgVgKgQQgKgPgUgBQgTABgKAPgAqiA6QgRgVAAggIAAgFQAAggARgVQARgUAggBQAaABAQAPQARAPgBAWIAAABIgYAAQAAgNgKgKQgJgKgPAAQgVAAgJAPQgJAQAAAWIAAAFQAAAXAJAOQAJAPAVABQANgBALgHQAKgJAAgMIAYAAIAAABQABATgSAPQgRAPgYAAQggAAgRgVgASUBMIAAgcIAbAAIAAAcgAP5BMIAAiTIAbAAIAAA5IAlAAQAaAAAPAMQAPALAAAVQAAAUgPANQgPANgaAAgAQUA3IAlAAQAOAAAIgHQAGgHABgKQgBgKgGgIQgIgHgOgBIglAAgAJnBMIAAh9IgxAAIAAgWIB8AAIAAAWIgwAAIAAB9gAH+BMIAAhoIgBgBIhBBpIgbAAIAAiTIAbAAIAABoIABAAIBBhoIAbAAIAACTgAFdBMIAAg6IgkAAIgjA6IgdAAIAlg+QgNgEgIgLQgHgLAAgOQgBgTAQgNQAPgNAaAAIA+AAIAACTgAEkgqQgHAHAAAJQAAALAGAGQAHAIANgBIAmAAIAAgvIgjAAQgOgBgIAIgADABMIAAg/IhCAAIAAA/IgbAAIAAiTIAbAAIAABAIBCAAIAAhAIAbAAIAACTgAAhBMIAAhoIgBgBIhABpIgbAAIAAiTIAbAAIAABoIABAAIBAhoIAbAAIAACTgArvBMIAAhoIgBgBIhBBpIgbAAIAAiTIAbAAIAABoIAAAAIBChoIAbAAIAACTgAwyBMIAAixIhhAAIAACxIgbAAIAAjGICXAAIAADGgASUALIAAiFIAbAAIAACFgAG/hpQgLgKABgQIAAgBIAUAAQAAAJAGAGQAEAFAKABQAKgBAFgFQAEgGAAgJIAVAAIABABQAAAQgLAKQgLAKgTAAQgTAAgLgKg");
	this.shape.setTransform(124.5,23.8);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol20, new cjs.Rectangle(0,0,248.8,41), null);


(lib.Symbol19 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFD700").s().p("A35DwIAAnfMAvzAAAIAAHfg");
	this.shape.setTransform(153,24);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol19, new cjs.Rectangle(0,0,306,48), null);


(lib.Symbol18 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("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");
	this.shape.setTransform(151,13.8);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol18, new cjs.Rectangle(0,0,302,27.7), null);


(lib.Symbol17 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("AGjCFIAAkGIAuAAIADAXQAIgNAMgHQAMgGAQAAQAjAAATAbQAVAcAAAtIAAADQAAAqgVAZQgTAagjAAQgPAAgMgGQgLgFgIgLIAABbgAHjhYQgIAFgFAIIAABTQAFAIAIAEQAIAEAMAAQASAAAKgOQAIgOAAgZIAAgDQAAgcgJgQQgJgRgSAAQgMAAgIAFgAsFCFIAAkGIAvAAIADAXQAJgNAMgHQALgGAQAAQAjAAATAbQAUAcAAAtIAAADQAAAqgUAZQgTAagjAAQgPAAgMgGQgLgFgIgLIAABbgArEhYQgIAFgFAIIAABTQAFAIAIAEQAIAEAMAAQASAAAKgOQAIgOAAgZIAAgDQAAgcgJgQQgJgRgTAAQgLAAgIAFgAMwAxQgQgQAAgaQAAgbAVgQQAWgPApAAIAcAAIAAgPQAAgOgGgHQgIgIgOAAQgNAAgGAGQgHAGAAALIgxAAIAAgBQgCgYAWgSQAXgRAkAAQAiAAAVARQAVARAAAgIAABOQAAANACAMQACAMAEALIgzAAIgEgNIgDgPQgIAOgNAJQgNAJgSAAQgeAAgQgPgANcgOQgJAIAAALQAAAJAGAGQAGAGALAAQAOAAALgHQAKgGADgJIAAgaIgcAAQgQAAgIAIgADoAlQgYgbAAgqIAAgEQAAgqAYgbQAXgbAqAAQArAAAXAbQAYAbAAAqIAAAEQAAAqgYAbQgXAbgrAAQgpAAgYgbgAEMhNQgJAQAAAZIAAAEQAAAaAJAPQAJAPAUAAQAVAAAJgPQAJgPAAgaIAAgEQAAgZgKgQQgIgQgVAAQgTAAgKAQgAifAlQgWgbAAgpIAAgFQAAgqAWgbQAYgbAqAAQAjAAAWAUQAVAUgBAgIAAABIgvAAQAAgOgIgKQgIgKgOAAQgUAAgJAQQgJAQAAAZIAAAFQAAAZAJAPQAJAPAUAAQANAAAJgIQAIgIAAgMIAvAAIAAABQABAcgWATQgXAUghAAQgqAAgYgbgAozAxQgQgQAAgaQgBgbAWgQQAWgPApAAIAcAAIAAgPQABgOgIgHQgGgIgPAAQgMAAgHAGQgHAGAAALIgxAAIAAgBQgCgYAXgSQAWgRAjAAQAiAAAWARQAVARAAAgIAABOQAAANACAMQACAMAFALIg0AAIgEgNIgEgPQgHAOgNAJQgNAJgSAAQgeAAgQgPgAoIgOQgIAIAAALQAAAJAGAGQAHAGALAAQANAAALgHQAKgGADgJIAAgaIgcAAQgQAAgJAIgAKfA8IAAiVIg8AAIAAgoICsAAIAAAoIg9AAIAACVgAB+A8IAAiVIg/AAIAACVIgzAAIAAi9ICmAAIAAC9gAkHA8IAAhIIg+AAIAABIIg0AAIAAi9IA0AAIAABNIA+AAIAAhNIAzAAIAAC9gAuPA8IAAiVIg9AAIAAgoICsAAIAAAoIg8AAIAACVg");
	this.shape.setTransform(97.3,13.3);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol17, new cjs.Rectangle(0,0,194.5,26.7), null);


(lib.Symbol16 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("AHRBwQgYgcAAgqIAAgEQAAgpAYgbQAXgbArAAQApAAAYAbQAZAbAAApIAAAEQAAAqgZAcQgYAagpABQgrgBgXgagAH1gCQgJAPAAAZIAAAEQAAAZAJARQAJAPAVAAQATAAAKgPQAJgRAAgZIAAgEQAAgZgKgPQgJgQgTAAQgVAAgJAQgABvBwQgXgcAAgqIAAgEQAAgpAXgbQAYgbAqAAQAqAAAYAbQAYAbAAApIAAAEQAAAqgYAcQgYAagqABQgqgBgYgagACTgCQgIAPgBAZIAAAEQABAZAIARQAKAPAUAAQAUAAAKgPQAJgRAAgZIAAgEQAAgZgKgPQgJgQgUAAQgUAAgKAQgAhZBxQgYgZAAgoIAAgaQAAg9AYgjQAYghAngIQASgDAIgEQAHgFAAgLIAoAAIAAABQABAjgRAKQgPALgnAHQgUADgLAMQgMAMgEAWIABABQAKgJANgFQAMgGAPAAQAnAAAXAZQAWAXAAAoIAAAEQAAAogXAZQgZAagpAAQgpAAgYgagAg1AIQgJAOAAAWIAAAEQAAAXAJAOQAKAOATAAQAVAAAIgOQAJgOAAgXIAAgEQAAgWgKgOQgHgMgVAAQgUAAgJAMgAkiB0QgYgVgFglIgbAAIAABNIgyAAIAAi9IAyAAIAABIIAcAAQAFghAYgWQAWgUAlAAQAqAAAZAbQAXAbAAApIAAAEQAAAqgXAcQgZAagqABQglAAgXgXgAkDgCQgJAPAAAZIAAAEQAAAZAJARQAJAPAUAAQAVAAAJgPQAJgRAAgZIAAgEQAAgZgJgPQgKgQgUAAQgUAAgJAQgAEpCHIAAi9ICCAAIAAAoIhOAAIAACVgAnpCHIAAiVIguAAIAAAnQAAA5gQAaQgSAbgqAAIgIAAIgBgoIAGAAQARAAAGgPQAFgOAAgoIAAhQICUAAIAAC9g");
	this.shape.setTransform(62.1,13.9);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol16, new cjs.Rectangle(0,0,124.1,27.7), null);


(lib.Symbol15 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("AjeCFIAAkGIAaAAIAGAZQAKgOAOgHQANgHASAAQAkAAATAcQAUAcAAAuIAAADQAAAogUAaQgTAZgjAAQgSAAgMgGQgOgGgJgMIAABdgAiqhiQgLAHgGAMIAABbQAGAMALAGQAKAHAPAAQAaAAAMgSQANgSAAgcIAAgEQAAgggNgVQgNgUgZAAQgPAAgKAGgAGCAxQgQgPAAgbQABgaAWgQQAWgPAlAAIAmAAIAAgTQAAgRgKgJQgKgKgTAAQgQAAgLAIQgLAJAAAMIghAAIAAgBQgBgVAVgRQAVgRAgAAQAgAAAUAQQAUAQAAAfIAABbIABAUQABAJACAJIgjAAIgCgPIgCgNQgKANgPAKQgQAJgSAAQgeAAgPgPgAGigQQgNAKAAANQAAANAIAHQAIAIAQAAQASAAAPgKQAQgKAEgMIAAgeIgnAAQgUAAgNALgAgCAlQgYgbAAgqIAAgEQAAgqAYgbQAVgbAoAAQAnAAAXAbQAXAbAAAqIAAAEQAAAqgXAbQgXAbgnAAQgoAAgVgbgAAVhVQgNAUAAAdIAAAEQAAAeANATQAMAUAaAAQAZAAANgUQAMgTAAgeIAAgEQAAgdgMgUQgNgUgZAAQgZAAgNAUgAJxA8IAAiiIg+AAIAAgbICeAAIAAAbIg+AAIAACigAE2A8IhBhPIgWAAIAABPIgjAAIAAi9IAjAAIAABQIATAAIA/hQIApAAIABABIhLBaIBQBhIAAABgAkzA8IAAiiIhUAAIAACiIgjAAIAAi9ICaAAIAAC9gApZA8IAAiGIgBAAIhUCGIgjAAIAAi9IAjAAIAACGIABAAIBUiGIAjAAIAAC9g");
	this.shape.setTransform(72.2,13.3);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol15, new cjs.Rectangle(0,0,144.3,26.7), null);


(lib.Symbol14 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("AllClIAAkHIAaAAIAGAZQAJgOAOgHQAOgHATAAQAjAAATAcQAUAcAAAtIAAAEQAAApgUAZQgTAZgjAAQgSAAgMgGQgOgFgJgNIAABegAkxhCQgLAGgGAMIAABbQAGAMALAGQAKAHAQAAQAZAAAMgSQAMgSAAgcIAAgEQAAgfgMgWQgNgUgYAAQgQAAgKAHgAGFCUIAAg5IiCAAIAAA5IggAAIgDhUIAPAAQAPgRAHgSQAIgSACglIAEhIIB8AAIAACiIAZAAIgDBUgAEygaQgBAfgIAVQgHAXgLAPIBVAAIAAiDIg4AAgAHVBQQgQgPAAgbQABgbAWgOQAWgQAmAAIAlAAIAAgTQAAgRgKgJQgKgKgTAAQgQAAgLAJQgLAIAAAMIggAAIgBgBQgBgVAVgRQAVgRAgAAQAhAAATARQAUAQAAAeIAABbIABAUQABAKACAIIgjAAIgCgOIgCgOQgJAOgQAJQgPAJgTAAQgeAAgPgPgAH1AOQgNALAAAOQAAAMAIAIQAIAHAQAAQASAAAQgJQAPgKAEgNIAAgeIgnAAQgUAAgNAKgAiIBFQgYgbAAgqIAAgHQAAgoAYgbQAYgbAhAAQAnAAAUAXQATAXAAAnIAAAVIh7AAIgBABQABAbANASQANARAZAAQARAAANgFQANgFALgJIANAWQgLALgQAGQgQAHgYAAQgoAAgXgagAhsg7QgNAOgDAXIABAAIBXAAIAAgFQABgUgLgOQgLgNgWAAQgRAAgMAPgACcBbIAAhQIhUAAIAABQIgkAAIAAi9IAkAAIAABSIBUAAIAAhSIAiAAIAAC9gAmlBbIgXhCIhsAAIgYBCIgkAAIBjj/IAeAAIBhD/gAodgCIBWAAIgqh1IgBAAg");
	this.shape.setTransform(61.3,16.5);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol14, new cjs.Rectangle(0,0,122.5,32.9), null);


(lib.Symbol12 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("EiV/AHCIAAuDMEr/AAAIAAODg");
	this.shape.setTransform(960,45);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol12, new cjs.Rectangle(0,0,1920,90), null);


(lib.Symbol10 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.instance = new lib.car_wh_left();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol10, new cjs.Rectangle(0,0,56,56), null);


(lib.Symbol8 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.instance = new lib.car_wh_right();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol8, new cjs.Rectangle(0,0,54,54), null);


(lib.Symbol6 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.instance = new lib.wheel_left();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol6, new cjs.Rectangle(0,0,139,139), null);


(lib.Symbol5 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.instance = new lib.wheel_right();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol5, new cjs.Rectangle(0,0,141,141), null);


(lib.Symbol4 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.instance = new lib.pic1();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol4, new cjs.Rectangle(0,0,559,192), null);


(lib.Symbol3 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("AEjBxQgYgZAAgoIAAgaQAAg9AZgjQAYghAngIQARgDAJgEQAHgFAAgLIApAAIAAABQAAAjgQAKQgQALgoAHQgTADgMAMQgLANgEAVIABABQAJgIANgGQANgGAOABQAoAAAXAYQAXAYAAAnIAAAEQAAAogYAZQgYAZgqAAQgqAAgYgZgAFIAJQgJANAAAWIAAAEQAAAXAJAOQAJAOAUAAQAUAAAJgOQAKgOgBgXIAAgEQAAgWgJgNQgJgNgUAAQgUAAgJANgABVBwQgYgbAAgrIAAgEQAAgqAYgbQAXgbArAAQAqAAAYAbQAYAbAAAqIAAAEQAAArgYAbQgYAbgqgBQgqABgYgbgAB6gCQgKAPABAZIAAADQgBAbAKAQQAIAPAVAAQAUAAAJgPQAJgQAAgbIAAgDQAAgZgJgPQgJgQgUAAQgUAAgJAQgAmGBwQgYgbAAgrIAAgEQAAgqAYgbQAXgbAqAAQArAAAXAbQAYAbAAAqIAAAEQAAArgYAbQgXAbgrgBQgpABgYgbgAligCQgJAPAAAZIAAADQAAAbAJAQQAJAPAUAAQAVAAAJgPQAJgQAAgbIAAgDQAAgZgKgPQgIgQgVAAQgTAAgKAQgAPjCHIAAh0IgBAAIg+B0IgzAAIAAi9IAzAAIAAByIABAAIA+hyIA0AAIAAC9gAMZCHIAAiVIguAAIAAAnQABA5gSAbQgRAagpAAIgJAAIAAgpIAFAAQARAAAGgOQAFgOAAgoIAAhQICUAAIAAC9gAJOCHIAAh0IgBAAIg+B0IgzAAIAAi9IAzAAIAAByIABAAIA+hyIA0AAIAAC9gAgZCHIAAhxIgCAAIgsBxIgiAAIgrhwIgBABIAABvIgzAAIAAi9IBAAAIAvCBIABAAIAyiBIA/AAIAAC9gAohCHIAAiVIg8AAIAAgoICsAAIAAAoIg9AAIAACVgAsaCHIAAi9IBPAAQAlAAAWANQAVANAAAbQAAAMgIALQgJAKgRAGQAWAEALAMQALALAAAQQAAAbgUANQgVAOglAAgArnBgIAoAAQANAAAHgFQAGgFAAgKQABgKgHgFQgGgGgOABIgoAAgArnAWIAdAAQAPAAAGgEQAHgFAAgJQAAgJgHgFQgIgEgOAAIgcAAgAtlCHIgRg3IhaAAIgRA3Ig1AAIBZj/IA0AAIBYD/gAvEAnIBBAAIgfhjIgBAAg");
	this.shape.setTransform(106.9,26.2);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol3, new cjs.Rectangle(0,0,215.5,51.5), null);


(lib.Symbol2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("ALNCcIAAg5IhxAAIAAA5IgwAAIgEhgIARAAQARgSAGgSQAGgSABggIABhAICMAAIAACWIAcAAIgEBggAKKgaQgBAcgGAVQgGAWgKAPIA+AAIAAhsIgmAAgAGKBNQgYgaAAgpIAAgHQAAgpAXgcQAXgcAoABQAmAAAVAXQAWAXAAAnIAAAbIhyAAIAAACQABASALANQALAMAUgBQARAAALgCQALgEANgHIAOAfQgLAJgTAHQgTAGgXAAQgpAAgZgagAGxgrQgIALgCASIAAABIA/AAIAAgFQAAgQgHgKQgIgKgPAAQgPAAgIALgAjJBNQgXgcAAgqIAAgFQAAgpAXgbQAXgbAqAAQAjAAAWAUQAVAUAAAhIgBABIgvAAQAAgPgIgKQgIgKgOAAQgUAAgJAQQgIAQAAAYIAAAFQAAAaAIAPQAJAPAUAAQAOABAIgJQAIgHAAgNIAvAAIABAAQAAAdgWATQgXAUghAAQgqAAgXgagAmTBNQgXgcAAgqIAAgFQAAgpAXgbQAYgbAqAAQAqAAAYAbQAYAbAAApIAAAFQAAAqgYAcQgYAagqAAQgqAAgYgagAluglQgJAPAAAYIAAAEQAAAaAJAQQAJAPAUAAQAUAAAKgPQAJgQAAgaIAAgEQAAgYgKgPQgJgQgUgBQgUABgJAQgAsYBNQgZgaAAgpIAAgHQAAgpAYgcQAXgcAnABQAnAAAVAXQAVAXAAAnIAAAbIhxAAIgBACQACASALANQALAMATgBQARAAALgCQAMgEANgHIAOAfQgMAJgTAHQgSAGgXAAQgpAAgZgagArxgrQgJALgCASIABABIA/AAIAAgFQAAgQgIgKQgHgKgQAAQgOAAgIALgAPbBjIAAi9IAzAAIAAC9gAMfBjIAAi9IA0AAIAAA+IAkAAQAmAAAWASQAWAQAAAdQAAAdgWARQgWASgmAAgANTA8IAkAAQAPAAAIgHQAIgHAAgLQAAgKgIgHQgIgIgPAAIgkAAgAEeBjIAAiVIg/AAIAACVIgzAAIAAi9ICmAAIAAC9gABTBjIAAhzIgBAAIg+BzIgyAAIAAi9IAyAAIAABzIABAAIA+hzIA0AAIAAC9gAn7BjIAAiVIguAAIAAApQAAA4gRAaQgRAbgqgBIgIAAIgBgoIAGAAQARAAAGgOQAFgPAAgnIAAhRICUAAIAAC9gAwNBjIAAj+IBWAAQAtAAAaARQAZARAAAjQAAATgJANQgKAPgSAGQAXAFAMARQALAPAAAVQAAAlgYASQgYATgtAAgAvaA8IAvAAQAVAAAKgJQALgJAAgRQAAgTgJgJQgJgKgUABIgzAAgAvagwIAlAAQAUAAAMgIQALgJAAgQQAAgSgLgJQgMgIgWAAIgjAAg");
	this.shape.setTransform(108.3,29.8);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol2, new cjs.Rectangle(0,0,217.7,51.5), null);


(lib.Symbol1 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#1E1E1E").s().p("AmPCkIAAkGIAvAAIADAXQAIgMAMgIQAMgGAQAAQAjAAATAcQAUAbAAAtIAAACQAAArgUAaQgTAZgjAAQgPAAgMgFQgLgGgIgLIAABbgAlOg5QgJAFgEAJIAABSQAEAIAJAFQAIADAMAAQASAAAJgOQAJgOAAgaIAAgCQAAgbgJgRQgJgRgTAAQgLABgIAEgAi9BQQgRgPAAgbQAAgcAWgPQAVgPAqAAIAcAAIAAgPQAAgNgHgIQgHgIgOAAQgNAAgGAGQgHAHAAAKIgyAAIAAgBQgBgYAWgRQAWgSAkAAQAiAAAWARQAVASAAAfIAABOQAAANACAMQACAMAEALIgzAAIgFgNIgDgPQgIAOgMAJQgNAJgSAAQgeAAgQgPgAiSARQgIAIAAALQAAAJAGAHQAGAFALAAQAOAAAKgGQALgHADgJIAAgbIgcAAQgRAAgIAJgAo6BFQgZgaAAgpIAAgGQAAgqAYgcQAXgcAnABQAnAAAVAXQAVAXAAAnIAAAbIhxAAIgBACQACASALANQALAMATgBQARAAALgCQAMgEANgHIAOAfQgMAJgTAHQgSAGgXAAQgpAAgZgagAoTgzQgJALgCASIABABIA/AAIAAgFQAAgQgIgKQgHgKgQAAQgOAAgIALgAvEBQQgQgPAAgbQAAgcAWgPQAVgPApAAIAdAAIAAgPQAAgNgHgIQgHgIgPAAQgMAAgHAGQgHAHAAAKIgxAAIAAgBQgCgYAXgRQAWgSAkAAQAiAAAVARQAVASAAAfIAABOQAAANADAMQABAMAFALIgzAAIgFgNIgDgPQgIAOgNAJQgNAJgSAAQgeAAgQgPgAuYARQgJAIAAALQAAAJAGAHQAHAFALAAQANAAALgGQAKgHAEgJIAAgbIgdAAQgQAAgIAJgAR8BbIAAi9IAzAAIAAC9gAPABbIAAi9IA0AAIAAA+IAkAAQAmAAAWASQAWARAAAcQAAAdgWARQgWASgmAAgAP0A0IAkAAQAPAAAIgHQAIgHAAgLQAAgKgIgIQgIgHgPAAIgkAAgAMvBbIAAiVIg8AAIAAgoICsAAIAAAoIg9AAIAACVgAKuBbIgfg/IgfA/Ig6AAIA7hfIg6heIA6AAIAcA9IACAAIAdg9IA6AAIg6BeIA8BfgAHoBbIAAhCIgfAAIglBCIg0AAIAqhKQgRgJgJgMQgKgOAAgRQAAgbAWgSQAWgSAmAAIBTAAIAAC9gAGxgzQgIAIAAAKQAAAKAIAHQAIAHAOABIAhAAIAAgyIggAAQgPAAgIAHgADIBbIAAhzIgBAAIg+BzIgzAAIAAi9IAzAAIAABzIABAAIA+hzIAzAAIAAC9gArYBbIAAiVIg8AAIAAgoICsAAIAAAoIg8AAIAACVgAwaBbIhFhqIgcAAIAABqIgzAAIAAj+IAzAAIAABoIAWAAIBEhoIA/AAIhWB3IBdCHg");
	this.shape.setTransform(124.4,30.6);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.Symbol1, new cjs.Rectangle(0,0,249.9,51.5), null);


(lib.Symbol13 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_39 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).wait(39).call(this.frame_39).wait(1));

	// Symbol 18
	this.instance = new lib.Symbol18();
	this.instance.parent = this;
	this.instance.setTransform(815.5,26.2,1,1,0,0,0,151,13.8);
	this.instance.alpha = 0;
	this.instance._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(20).to({_off:false},0).to({x:785.5,alpha:1},19,cjs.Ease.get(1)).wait(1));

	// Symbol 17
	this.instance_1 = new lib.Symbol17();
	this.instance_1.parent = this;
	this.instance_1.setTransform(555.5,33.7,1,1,0,0,0,97.3,13.3);
	this.instance_1.alpha = 0;
	this.instance_1._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_1).wait(15).to({_off:false},0).to({x:525.5,alpha:1},19,cjs.Ease.get(1)).wait(6));

	// Symbol 16
	this.instance_2 = new lib.Symbol16();
	this.instance_2.parent = this;
	this.instance_2.setTransform(385.3,26.3,1,1,0,0,0,62.1,13.9);
	this.instance_2.alpha = 0;
	this.instance_2._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).wait(10).to({_off:false},0).to({x:355.3,alpha:1},19,cjs.Ease.get(1)).wait(11));

	// Symbol 15
	this.instance_3 = new lib.Symbol15();
	this.instance_3.parent = this;
	this.instance_3.setTransform(240.6,33.7,1,1,0,0,0,72.2,13.3);
	this.instance_3.alpha = 0;
	this.instance_3._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_3).wait(5).to({_off:false},0).to({x:210.6,alpha:1},19,cjs.Ease.get(1)).wait(16));

	// Symbol 14
	this.instance_4 = new lib.Symbol14();
	this.instance_4.parent = this;
	this.instance_4.setTransform(93.7,30.5,1,1,0,0,0,61.3,16.4);
	this.instance_4.alpha = 0;

	this.timeline.addTween(cjs.Tween.get(this.instance_4).to({x:63.7,alpha:1},19,cjs.Ease.get(1)).wait(21));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(32.4,14.1,122.5,32.9);


(lib.Symbol7 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_99 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).wait(99).call(this.frame_99).wait(1));

	// Layer 3
	this.instance = new lib.Symbol8();
	this.instance.parent = this;
	this.instance.setTransform(383,145,1,1,0,0,0,27,27);

	this.timeline.addTween(cjs.Tween.get(this.instance).to({rotation:1080},99,cjs.Ease.get(0.5)).wait(1));

	// Layer 2
	this.instance_1 = new lib.Symbol10();
	this.instance_1.parent = this;
	this.instance_1.setTransform(114,145,1,1,0,0,0,28,28);

	this.timeline.addTween(cjs.Tween.get(this.instance_1).to({rotation:1080},99,cjs.Ease.get(0.5)).wait(1));

	// Layer 1
	this.instance_2 = new lib.car();
	this.instance_2.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).wait(100));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,444,186);


(lib.bike = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_99 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).wait(99).call(this.frame_99).wait(1));

	// Layer 1
	this.instance = new lib.bike1();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(100));

	// Layer 3
	this.instance_1 = new lib.Symbol5();
	this.instance_1.parent = this;
	this.instance_1.setTransform(277.5,147.5,1,1,0,0,0,70.5,70.5);

	this.timeline.addTween(cjs.Tween.get(this.instance_1).to({rotation:1800},99,cjs.Ease.get(0.5)).wait(1));

	// Layer 2
	this.instance_2 = new lib.Symbol6();
	this.instance_2.parent = this;
	this.instance_2.setTransform(69.5,148.5,1,1,0,0,0,69.5,69.5);

	this.timeline.addTween(cjs.Tween.get(this.instance_2).to({rotation:1800},99,cjs.Ease.get(0.5)).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,348,218);


(lib.main = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 18
	this.instance = new lib.Symbol20();
	this.instance.parent = this;
	this.instance.setTransform(960,45.1,0.405,0.405,0,0,0,124.4,20.5);
	this.instance.alpha = 0;
	this.instance._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(4).to({_off:false},0).to({regY:20.4,scaleX:1,scaleY:1,y:45,alpha:1},14,cjs.Ease.get(1)).wait(31).to({regY:20.5,scaleX:0.41,scaleY:0.41,y:45.1,alpha:0},12,cjs.Ease.get(-1)).to({_off:true},1).wait(465));

	// Layer 14
	this.instance_1 = new lib.Symbol19();
	this.instance_1.parent = this;
	this.instance_1.setTransform(960.1,45,0.026,1,0,0,0,153.1,24);

	this.timeline.addTween(cjs.Tween.get(this.instance_1).to({regX:153,scaleX:1,x:960},14,cjs.Ease.get(1)).wait(38).to({regX:153.1,scaleX:0.03,x:960.1},12,cjs.Ease.get(-1)).to({_off:true},1).wait(462));

	// Layer 17
	this.instance_2 = new lib.Symbol13();
	this.instance_2.parent = this;
	this.instance_2.setTransform(926,43.1,1,1,0,0,0,470.2,25.7);
	this.instance_2._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).wait(427).to({_off:false},0).wait(89).to({alpha:0},10).wait(1));

	// Layer 16
	this.instance_3 = new lib.Symbol12();
	this.instance_3.parent = this;
	this.instance_3.setTransform(960,45.1,1,1.289,0,0,0,960,45);
	this.instance_3.alpha = 0;
	this.instance_3._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_3).wait(411).to({_off:false},0).to({alpha:1},15).wait(101));

	// Автомобили
	this.instance_4 = new lib.Symbol3();
	this.instance_4.parent = this;
	this.instance_4.setTransform(643.1,39.8,1,1,0,0,0,107.8,25.7);
	this.instance_4.alpha = 0;
	this.instance_4._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_4).wait(238).to({_off:false},0).to({x:693.1,alpha:1},14,cjs.Ease.get(1)).wait(25).to({regX:107.9,regY:25.6,scaleX:0.68,scaleY:0.68,x:640.7,y:16.5},60).wait(74).to({_off:true},15).wait(101));

	// Layer 15
	this.instance_5 = new lib.Symbol7();
	this.instance_5.parent = this;
	this.instance_5.setTransform(136.1,24,1,1,0,0,0,222,93);
	this.instance_5.alpha = 0;
	this.instance_5._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_5).wait(238).to({_off:false},0).to({scaleX:0.9,scaleY:0.9,x:203.3,y:29.2,alpha:1},19,cjs.Ease.get(-0.5)).to({regX:222.4,regY:93.2,scaleX:0.5,scaleY:0.5,x:486.4,y:50.7},80,cjs.Ease.get(0.5)).wait(74).to({_off:true},15).wait(101));

	// Велосипеды
	this.instance_6 = new lib.Symbol2();
	this.instance_6.parent = this;
	this.instance_6.setTransform(917.1,39.8,1,1,0,0,0,108.9,25.7);
	this.instance_6.alpha = 0;
	this.instance_6._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_6).wait(150).to({_off:false},0).to({x:967.1,alpha:1},14,cjs.Ease.get(1)).wait(25).to({regX:109.1,regY:25.8,scaleX:0.68,scaleY:0.68,x:1027.3,y:16.8},60,cjs.Ease.get(0.5)).wait(162).to({_off:true},15).wait(101));

	// Layer 13
	this.instance_7 = new lib.bike();
	this.instance_7.parent = this;
	this.instance_7.setTransform(174,-19,1,1,0,0,0,174,109);
	this.instance_7.alpha = 0;
	this.instance_7._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_7).wait(150).to({_off:false},0).to({scaleX:0.89,scaleY:0.89,x:313.1,y:-6.4,alpha:1},19,cjs.Ease.get(-0.5)).to({regX:174.2,scaleX:0.41,scaleY:0.41,x:898.6,y:46.8},80,cjs.Ease.get(0.5)).wait(162).to({_off:true},15).wait(101));

	// Катера и яхты
	this.instance_8 = new lib.Symbol1();
	this.instance_8.parent = this;
	this.instance_8.setTransform(1241,39.8,1,1,0,0,0,125,25.7);
	this.instance_8.alpha = 0;
	this.instance_8._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_8).wait(62).to({_off:false},0).to({x:1291,alpha:1},14,cjs.Ease.get(1)).wait(25).to({scaleX:0.7,scaleY:0.7,x:1435.9,y:18.3},60,cjs.Ease.get(0.5)).wait(250).to({_off:true},15).wait(101));

	// Layer 9
	this.instance_9 = new lib.Symbol4();
	this.instance_9.parent = this;
	this.instance_9.setTransform(578.9,17,0.905,0.905,0,0,0,279.5,96);
	this.instance_9.alpha = 0;
	this.instance_9._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_9).wait(62).to({_off:false},0).to({regX:279.6,scaleX:0.83,scaleY:0.83,x:720.9,y:24.3,alpha:1},19,cjs.Ease.get(-0.5)).to({regY:96.1,scaleX:0.51,scaleY:0.51,x:1318.3,y:54.7},80,cjs.Ease.get(0.5)).wait(250).to({_off:true},15).wait(101));

	// BG
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("EiV/AHCIAAuDMEr/AAAIAAODg");
	this.shape.setTransform(960,45.1);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(527));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,1920,90.1);


// stage content:
(lib.Multiprokat_1920x90 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		var page_canvas = document.getElementsByTagName("canvas")[0];
				var r = this;
				page_canvas.style.margin = "0 auto";
				var viewport = document.querySelector('meta[name=viewport]');
				var viewportContent = 'width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0';
				
				if (viewport === null) {
				 var head = document.getElementsByTagName('head')[0];
				 viewport = document.createElement('meta');
				 viewport.setAttribute('name', 'viewport');
				 head.appendChild(viewport);
				}
				
				viewport.setAttribute('content', viewportContent);
				var page_body = document.getElementsByTagName("body")[0];
				
				
				function onResize() {
				 var newWidth = window.innerWidth;
				 var newHeight = window.innerHeight;
				 stage.width = newWidth;
				 stage.height = newHeight;
				 r.main.x = window.innerWidth/2;
				}
				
				window.onresize = function () {
				 onResize();
				}
				
				onResize();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(1));

	// Layer 1
	this.main = new lib.main();
	this.main.parent = this;
	this.main.setTransform(960,45,1,1,0,0,0,960,45);

	this.timeline.addTween(cjs.Tween.get(this.main).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(960,45.1,1920,90);
// library properties:
lib.properties = {
	width: document.documentElement.clientWidth,
	height: 90,
	fps: 30,
	color: "#FFFFFF",
	opacity: 1.00,
	webfonts: {},
	manifest: [
		{src:"assets/html5_banner/images/bike1.png", id:"bike1"},
		{src:"assets/html5_banner/images/car.png", id:"car"},
		{src:"assets/html5_banner/images/car_wh_left.png", id:"car_wh_left"},
		{src:"assets/html5_banner/images/car_wh_right.png", id:"car_wh_right"},
		{src:"assets/html5_banner/images/pic1.jpg", id:"pic1"},
		{src:"assets/html5_banner/images/wheel_left.png", id:"wheel_left"},
		{src:"assets/html5_banner/images/wheel_right.png", id:"wheel_right"}
	],
	preloads: []
};




})(lib = lib||{}, images = images||{}, createjs = createjs||{}, ss = ss||{}, AdobeAn = AdobeAn||{});
var lib, images, createjs, ss, AdobeAn;