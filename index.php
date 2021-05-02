<html>
    <head>
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="bar.css"/>
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div id="goods_wrapper"></div>
        <div id="buildings_wrapper"></div>
        <div id="pasbuildings_wrapper"></div>
        <div id="doctrines_wrapper" class="doctrines_wrapper">
            <!--<div id="haste_wrapper" class="doctrine_wrapper">
                <div id="haste_cooldown" class="doctrine_cooldown">
                    <div id="haste_button" class="doctrine_button" onClick="haste.activate()"></div>
                </div>
                <div id="haste_costs" class="doctrine_costs"></div>
            </div>-->
        </div>
        <div id="controlpanel">
            <div id="popularity_wrapper">
                <div id="popularity_text">Beliebtheit</div>
                <div id="popularity_value"></div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript"> 
ressources_names = ['holz','bretter','stein','ziegel','getreide','mehl','brot','eisenerz','eisen','fleisch','leder'];    
ressourcebuildings_names = ['holzfäller','sägewerk','steinbruch','steinmetz','getreidefarm','mühle','bäcker','eisenmine','eisenschmelze','jäger','gerber'];
doctrines_names = ['eifer'];
productivity = 5;
std_speedlevels = [0.000001,10,2,4,8,16,32,64,128];

function buildFrontend(ressources_names,ressourcebuildings_names,productivity){
    for(var x = 0; x<ressources_names.length; x++){
        var div = document.createElement("div");
        div.setAttribute('id',ressources_names[x] + '_wrapper');
        div.setAttribute('class','good_superwrapper');
        document.getElementById('goods_wrapper').appendChild(div);
        var div = document.createElement("div");
        div.setAttribute('id',ressources_names[x] + 'text');
        div.setAttribute('class','good_wrapper');
        document.getElementById(ressources_names[x] + '_wrapper').appendChild(div);
        var subimg = document.createElement("img");
        subimg.setAttribute('src','icons/'+ ressources_names[x] + 'bar.png');
        subimg.setAttribute('class','res_icon');
        document.getElementById(ressources_names[x] + 'text').appendChild(subimg);
        var secondDiv = document.createElement("div");
        secondDiv.setAttribute('id',ressources_names[x] + '_value');
        document.getElementById(ressources_names[x] + '_wrapper').appendChild(secondDiv);       
    }
    for(var x = 0; x<ressourcebuildings_names.length; x++){
        var div = document.createElement("div");
        div.setAttribute('id',ressourcebuildings_names[x] + '_wrapper');
        div.setAttribute('class','building_wrapper');
        document.getElementById('buildings_wrapper').appendChild(div);
        
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',ressourcebuildings_names[x] + '_image');
        subdiv.setAttribute('class','building_image');
        subdiv.innerHTML =  '<img src="icons/' + ressources_names[x] + 'building.png" style="width: auto;height:50px" class="res_icon">';
        document.getElementById(ressourcebuildings_names[x] + '_wrapper').appendChild(subdiv);    
        
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',ressourcebuildings_names[x] + '_lv');
        subdiv.setAttribute('class','building_lv');
        document.getElementById(ressourcebuildings_names[x] + '_wrapper').appendChild(subdiv); 
        
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',ressourcebuildings_names[x] + '_name');
        subdiv.setAttribute('class','building_name');
        subdiv.setAttribute('onClick',ressources_names[x]+'.pause()');
        subdiv.innerHTML =  ressourcebuildings_names[x].charAt(0).toUpperCase() + ressourcebuildings_names[x].slice(1);
        document.getElementById(ressourcebuildings_names[x] + '_wrapper').appendChild(subdiv);
        
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',ressourcebuildings_names[x] + '_upgradecosts');
        subdiv.setAttribute('class','building_upgradecosts');
        document.getElementById(ressourcebuildings_names[x] + '_wrapper').appendChild(subdiv);
        
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',ressourcebuildings_names[x] + '_upgrade');
        subdiv.setAttribute('class','building_upgrade');
        subdiv.setAttribute('onClick',ressources_names[x]+'.upgrade()');
        document.getElementById(ressourcebuildings_names[x] + '_wrapper').appendChild(subdiv);   
        
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',ressources_names[x] + 'bar_wrapper');
        subdiv.setAttribute('class','loadbar_wrapper');
        document.getElementById(ressourcebuildings_names[x] + '_wrapper').appendChild(subdiv);   
    }
    for(var x = 0; x<doctrines_names.length; x++){
        var div = document.createElement("div");
        div.setAttribute('id',doctrines_names[x] + '_wrapper');
        div.setAttribute('class','doctrine_wrapper');
        document.getElementById('doctrines_wrapper').appendChild(div);
        
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',doctrines_names[x] + '_cooldown');
        subdiv.setAttribute('class','doctrine_cooldown');
        document.getElementById(doctrines_names[x] + '_wrapper').appendChild(subdiv); 
    
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',doctrines_names[x] + '_costs');
        subdiv.setAttribute('class','doctrine_costs');
        document.getElementById(doctrines_names[x] + '_wrapper').appendChild(subdiv); 
    
        var subdiv = document.createElement("div");
        subdiv.setAttribute('id',doctrines_names[x] + '_button');
        subdiv.setAttribute('class','doctrine_button');
        subdiv.setAttribute('onClick', doctrines_names[x] + '.activate()');
        document.getElementById(doctrines_names[x] + '_cooldown').appendChild(subdiv);    
    }
}

function castMovingIcon(filename,position,direction,lifespan,fadeout,dimensions){
    movingIcon = {
        div: document.createElement("div"),
        filename : filename,
        position : position,
        direction : direction,
        dimensions : dimensions,
        lifespan : lifespan,
        fadeout : fadeout,
        timer : null,
        init : function(){
            this.div.style.position = 'absolute';
            this.div.style.display = 'block';
            this.div.style.backgroundSize = '100%';
            this.div.style.width = this.dimensions + 'px';
            this.div.style.height = this.dimensions + 'px';
            this.div.style.top = this.position[1];
            this.div.style.left = this.position[0];
            if(this.direction == 'top'){
                this.div.classList.add('topMargin');
            } else if (this.direction == 'right'){
                this.div.classList.add('rightMargin');
            } else if (this.direction == 'left'){
                this.div.classList.add('leftMargin');
            } else {
                this.div.classList.add('bottomMargin');
            }
            this.div.style.backgroundImage = 'url("icons/' + this.filename + '")';
            this.div.style.transition = this.lifespan + 's';
            document.getElementsByTagName('body')[0].appendChild(this.div);
            setTimeout(this.animation.bind(this), 1);
            setTimeout(this.finished.bind(this), (this.lifespan * 1000));
        },
        animation : function(){
            this.div.classList.add('noMargin');
            this.div.classList.remove('topMargin');
            this.div.classList.remove('rightMargin');
            this.div.classList.remove('leftMargin');
            this.div.classList.remove('bottomMargin');
        },
        finished : function(){
            this.div.remove();
        }
    }
    movingIcon.init();
}

function newDoctrine(name,description,costs,cooldown,activationpoints,duration){
    doctrine = {
        name : name,
        description: description,
        costs : costs,
        cooldown : cooldown,
        duration: duration,
        timeby: cooldown + 1,
        activationpoints: activationpoints,
        beginEffect : function(){},
        endEffect : function(){},
        unlocked : false,
        active : false,
        timer : null,
        cdtimer : null,
        upgradeRequirements: '',
        init : function(){
            if(this.activationpoints == 0){
                this.unlocked = true;
            }
            document.getElementById(this.name + '_button').innerHTML ='<b>' + (this.name.charAt(0).toUpperCase() + this.name.slice(1)) + '</b></br>' + this.description + '<br><i>(CD:' + this.cooldown + 's)</i>';
            this.refresh();
        }, 
        refresh : function(){
            if(this.activationpoints == 0){
                this.unlocked = true;
            }
            var costsstring = '';
            for (var x = 0; x < this.costs.length; x++){
                if(this.costs[x] != 0){
                    if(this.costs[x]>ressources[x].amount){
                        costsstring = costsstring + '<span style="color: red;" class="res_icon_inline"><b>'+this.costs[x] + '</b> ' + ressources[x].printIcon(20) + ' </span> ';
                    } else {
                        costsstring = costsstring + '<span class="res_icon_inline"><b>'+this.costs[x] + '</b> ' + ressources[x].printIcon(20) + ' </span> ';
                    }
                }
            }
            if( this.checkUpgradeRequirements() == false ){
                costsstring = costsstring  + '<span class="res_icon_inline">' + this.upgradeRequirements + '</span>';
            }
            if (costsstring == '') {
                costsstring = 'No Requirements';
            }

        document.getElementById(this.name + '_costs').innerHTML = costsstring;
        
        }, 
        activate : function(){
            var ok = true;
            for(var x = 0; x<this.costs.length;x++){
                if(ressources[x].amount < this.costs[x]){
                    
                    ok = false;
                }
            }
            if(this.checkUpgradeRequirements()== false){
                ok = false;
            }
            if(ok == true && this.active == false && ((this.cooldown - this.timeby) < 0) && (this.timeby > this.duration)){
                for(var x = 0; x<this.costs.length;x++){
                    ressources[x].amount = ressources[x].amount - this.costs[x];
                    ressources[x].refresh(false);
                }
                this.timer = setInterval(this.reset.bind(this),this.cooldown*1000);
                this.active = true;
                this.cdtimer = setInterval(this.procCD.bind(this),100);
                this.timeby = 0;
                this.beginEffect();
                this.refresh();
            }
        },
        reset : function(){
            this.timeby = this.timeby + 0.1;
            clearInterval(this.timer);
            clearInterval(this.cdtimer);
            document.getElementById(this.name + '_cooldown').style.background = 'radial-gradient(circle, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 100%)';
        },
        procCD : function(){
            this.timeby = this.timeby + 0.1;
            if (this.timeby > this.duration && this.active == true){
                this.active = false;
                this.endEffect();
            }
            var procentualCD = (100 - this.timeby/this.cooldown * 100);
            if (this.timeby > this.duration)
            {
                document.getElementById(this.name + '_cooldown').style.background = 'radial-gradient(circle, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.5) ' + procentualCD + '%, rgba(0,0,0,0) ' + procentualCD + '%, rgba(0,0,0,0) 100%)';
            } else {
                var percDur = (this.timeby/this.duration);
                document.getElementById(this.name + '_cooldown').style.background = 'radial-gradient(circle, rgba('+((55-(percDur*55)) + 200)+'200,100,0,0.7) 0%, rgba(200,100,0,0.7) ' + procentualCD + '%, rgba(0,0,0,0) ' + procentualCD + '%, rgba(0,0,0,0) 100%)';
            }
        },
        checkUpgradeRequirements : function(){return true;},
        
    }
    doctrine.init();
    return doctrine;
}

function offset(el) {
    var rect = el.getBoundingClientRect(),
    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
}

function newLoadBar(parent,name,startval,maxval,speed,sclass,color) {
        bar = {
        wait : false,
        finishedState : false,
        parent : parent,
        color: color,
        name : name,
        sclass : sclass,
        val : startval,
        max : maxval,
        speed: speed,
        pause : false,
        div_border: document.createElement("div"),
        div_scale: document.createElement("div"),
        div_bar: document.createElement("div"),
        timer : null,
        init : function() {
            this.div_border.setAttribute('id','loadbar_border_' + this.name);
            this.div_border.setAttribute('class',this.sclass);
            this.div_scale.setAttribute('id','loadbar_scale_' + this.name);
            this.div_bar.setAttribute('id','loadbar_bar_' + this.name);
            this.div_bar.style.backgroundColor = 'rgba(' + this.color + ',1)';
            this.div_bar.style.transition = 'all ' + (100/this.speed) + 's linear 0s';
            this.div_bar.style.transitionTimingFunction = 'linear';
            this.div_bar.classList.add('bar_100');
            document.getElementById(this.name + '_wrapper').appendChild(this.div_border);
            document.getElementById('loadbar_border_'+this.name).appendChild(this.div_scale);
            document.getElementById('loadbar_scale_'+this.name).appendChild(this.div_bar);
           
            this.timer = setTimeout(this.finished.bind(this), ((1000 * 100)/this.speed));
        },
        getVal : function() {
            var yet = Date.now();
            var roundtime = (100/this.speed)*1000;
            var difference = (yet - this.parent.lastupdate);
            //var theoreticalpercentage = this.bar.val / 100;
            var actualpercentage = difference/roundtime;
            var roundspassed = Math.floor(actualpercentage);
            /*console.log(this.name + ':');
            console.log('then: ' + this.lastupdate);
            console.log('yet: ' + yet);
            console.log('difference: ' + difference);
            console.log('roundtime: ' + roundtime);
            console.log('theoreticalpercentage: ' + theoreticalpercentage);
            console.log('actualpercentage: ' + actualpercentage);
            console.log('clean theoreticalpercentage: ' + (actualpercentage - Math.floor(actualpercentage)))
            console.log('= '+ Math.floor((actualpercentage - roundspassed)*100));
            console.log('rounds passed: ' + roundspassed);*/
            this.amount += Math.floor(actualpercentage);
            var exactBar = Math.floor((actualpercentage - roundspassed)*100);
            return exactBar;
        },
        setVal : function(v) {
            this.div_bar.classList.remove(...this.div_bar.classList);
            this.val = v;
            clearTimeout(this.timer);
            if(v == 0){
                this.timer = setTimeout(this.finished.bind(this), ((1000*(100-this.val))/this.speed));
                this.div_bar.style.transitionDuration = '0s';
                this.div_bar.classList.add('bar_0');
                this.finishedState == false;
                setTimeout(function(){
                    this.div_bar.classList.remove(...this.div_bar.classList);
                    this.div_bar.style.transition = 'all ' + ((1-(this.val/100)) * (100/this.speed)) + 's linear 0s';
                    this.div_bar.classList.add('bar_100');
                }.bind(this),1);
            } else {
                clearTimeout(this.timer);
                this.timer = setTimeout(this.finished.bind(this), ((1000*(100-this.val))/this.speed));
                this.div_bar.style.transitionDuration = '0s';
                console.log(this.val);
                this.div_bar.classList.add('bar_'+this.val);
                setTimeout(function(){
                    this.div_bar.classList.remove(...this.div_bar.classList);
                    this.div_bar.style.transition = 'all ' + ((1-(this.val/100)) * (100/this.speed)) + 's linear 0s';
                    this.div_bar.classList.add('bar_100');
                }.bind(this),1);
                

            }
        },
        setSpeed : function(s) {
            var g = Date.now() - this.parent.lastupdate;
            var p = g/(100000/this.speed);
            
            this.speed = s;
            this.setVal(Math.floor(p*100));
            //this.redraw();
        },
        finished : function() {
            this.parent.amount++;
            this.parent.lastupdate = Date.now();
            for( var x = 0 ; x < doctrines.length ; x++){
                doctrines[x].refresh();
            }
            
            this.parent.refresh(false);
            for( var x = 0 ; x < ressources.length ; x++){
                if(this.parent != ressources[x]){
                    ressources[x].refresh(false);
                }
            }

            var cOffset = offset(document.getElementById('loadbar_border_' + this.name));
            var style = window.getComputedStyle(document.getElementById('loadbar_border_' + this.name));
            var posx = cOffset.left;
            var posy = cOffset.top;
            var dimx = style.getPropertyValue("width");
            var dimy = style.getPropertyValue("height");
            var posArray = ['calc(' + posx +  'px + ' + dimx + ' + ' + '10px)','calc(' + posy +  'px + ' + dimy + ' - ' + '40px)'];
            castMovingIcon(this.name + '.png',posArray,'top',1,true,30);
            if(this.pause == false){
                this.finishedState = false;
                this.setVal(0);
                //this.timer = setTimeout(this.finished.bind(this), ((1000*(100))/this.speed));
                return true;
            } else {
                this.finishedState = true;
                this.div_bar.classList.remove(...this.div_bar.classList);
                this.div_bar.style.transitionDuration = '0s';
                this.div_bar.classList.add('bar_0');
            } 
        },
        redraw : function() {
            //this.div_bar.innerHTML = Math.round(((this.val/this.max)*100)) + '%';
        }
    };
    bar.init();
    return bar;
    }

function newLoadBarProductivity(parent,name,startval,maxval,speed,sclass,color) {
    bar = {
        parent : parent,
        color: color,
        name : name,
        sclass : sclass,
        val : startval,
        max : maxval,
        speed: speed,
        pause : false,
        div_border: document.createElement("div"),
        div_scale: document.createElement("div"),
        div_bar: document.createElement("div"),
        timer : null,
        init : function() {
            this.div_border.setAttribute('id','loadbar_border_' + this.name);
            this.div_border.setAttribute('class',this.sclass);
            this.div_scale.setAttribute('id','loadbar_scale_' + this.name);
            this.div_bar.setAttribute('id','loadbar_bar_' + this.name);
            this.div_bar.style.backgroundColor = 'rgba(' + this.color + ',1)';
            this.div_bar.style.transition = 'all ' + (1/this.speed) + 's linear 0s';
            this.div_bar.style.transitionTimingFunction = 'linear';
            //this.div_bar.innerHTML = Math.round(((this.val/this.max)*100)) + '%';
            this.div_bar.classList.add('bar_'+(this.val + productivity));
            document.getElementById(this.name + '_wrapper').appendChild(this.div_border);
            document.getElementById('loadbar_border_'+this.name).appendChild(this.div_scale);
            document.getElementById('loadbar_scale_'+this.name).appendChild(this.div_bar);
           
            this.timer = setInterval(this.procBar.bind(this), (1000/this.speed));
        },
        procBar : function(){
            console.log(this.val);
            if(this.val >= this.max){
                if (this.finished()) {
                    var cOffset = offset(document.getElementById('loadbar_border_' + this.name));
                    var style = window.getComputedStyle(document.getElementById('loadbar_border_' + this.name));
                    var posx = cOffset.left;
                    var posy = cOffset.top;
                    var dimx = style.getPropertyValue("width");
                    var dimy = style.getPropertyValue("height");
                    var posArray = ['calc(' + posx +  'px + ' + dimx + ' + ' + '10px)','calc(' + posy +  'px + ' + dimy + ' - ' + '40px)'];
                    castMovingIcon(this.name + '.png',posArray,'top',1,true,30);
                }
            } else {
                if(this.pause== false){
                    this.incVal(productivity * 1);
                    if(this.val >= this.max){
                        if (this.finished()) {
                            var cOffset = offset(document.getElementById('loadbar_border_' + this.name));
                            var style = window.getComputedStyle(document.getElementById('loadbar_border_' + this.name));
                            var posx = cOffset.left;
                            var posy = cOffset.top;
                            var dimx = style.getPropertyValue("width");
                            var dimy = style.getPropertyValue("height");
                            var posArray = ['calc(' + posx +  'px + ' + dimx + ' + ' + '10px)','calc(' + posy +  'px + ' + dimy + ' - ' + '40px)'];
                            castMovingIcon(this.name + '.png',posArray,'top',1,true,30);
                        }
                    }
                }
            }
        },
        setVal : function(v) {
            this.div_bar.classList.remove(...this.div_bar.classList);
            this.val = v;
            if(v == 0){
                this.div_bar.classList.add('bar_0');
                setTimeout(function(){this.div_bar.classList.add('bar_' + Math.floor(productivity));}.bind(this),1);
            } else {
                if((this.val + productivity)>100){
                    this.div_bar.classList.add('bar_100');
                } else {
                    this.div_bar.classList.add('bar_'+(this.val + productivity));
                }
            }
            //console.log('from:');
            //console.log('bar_'+this.val);
            
            
             
            //console.log('to:');
            //console.log('bar_'+this.val);
            this.redraw();
        },
        setSpeed : function(s) {
            this.speed = s;
            clearInterval(this.timer);
            this.timer = setInterval(this.procBar.bind(this), (1000/this.speed));
            this.div_bar.style.transition = 'all ' + (1/this.speed) + 's linear 0s';
            this.redraw();
        },
        incVal : function(v) {
            this.setVal(this.val + v);
        },
        finished : function() {
            this.parent.amount++;
            this.parent.lastupdate= Date.now();
            for( var x = 0 ; x < doctrines.length ; x++){
                doctrines[x].refresh();
            }
            this.parent.refresh(false);
            for( var x = 0 ; x < ressources.length ; x++){
                if(this.parent != ressources[x]){
                    ressources[x].refresh(false);
                }
            }
            this.setVal(0);
            return true;
        },
        redraw : function() {
            //this.div_bar.innerHTML = Math.round(((this.val/this.max)*100)) + '%';
        }
    };
    bar.init();
    return bar;
}

function newRessource(name,buildingname,startval,startlevel,speedlevels,upgradecosts){
    res = {
    printIcon: function(size) {  
        return '<img src="icons/' + this.name + 'bar.png" style="width:'+size+'px;height:'+size+'px"class="res_icon">'; 
    },
    printBuildingIcon: function(size) {  
        return '<img src="icons/' + this.name + 'building.png" style="width:auto;height:'+size+'px"class="res_icon">'; 
    },
    name: name,
    buildingname: buildingname,
    bar: null,
    amount : startval,
    level : startlevel,
    speedlevels : speedlevels ,
    upgradecosts : upgradecosts,
    checkUpgradeRequirements : function(){return true;},
    upgradeRequirements: '',
    lastupdate: Date.now(),
    recalc : function() {
        if(this.bar && this.level > 0 && this.bar.pause == false && this.bar.wait == false){
                var yet = Date.now();
                var roundtime = (100/this.speedlevels[this.level])*1000;
                var difference = (yet - this.lastupdate);
                //var theoreticalpercentage = this.bar.val / 100;
                var actualpercentage = difference/roundtime;
                var roundspassed = Math.floor(actualpercentage);
                /*console.log(this.name + ':');
                console.log('then: ' + this.lastupdate);
                console.log('yet: ' + yet);
                console.log('difference: ' + difference);
                console.log('roundtime: ' + roundtime);
                console.log('theoreticalpercentage: ' + theoreticalpercentage);
                console.log('actualpercentage: ' + actualpercentage);
                console.log('clean theoreticalpercentage: ' + (actualpercentage - Math.floor(actualpercentage)))
                console.log('= '+ Math.floor((actualpercentage - roundspassed)*100));
                console.log('rounds passed: ' + roundspassed);*/
                this.amount += Math.floor(actualpercentage);
                var exactBar = Math.floor((actualpercentage - roundspassed)*100);
                //var snapBar = exactBar - (exactBar % Math.floor(productivity));
                this.bar.setVal(exactBar);
        }
    },
    refresh : function(recalc){
        
        if(this.amount == 0){
            document.getElementById(this.name + '_value').innerHTML = '';
            document.getElementById(this.name + '_wrapper').style.height = '40px';
        } else {
            document.getElementById(this.name + '_value').innerHTML = this.amount;
            document.getElementById(this.name + '_wrapper').style.height = '60px';
        }
        document.getElementById(this.buildingname + '_lv').innerHTML = this.level;
        if(this.level == 0){
            document.getElementById(this.buildingname + '_upgrade').innerHTML = 'Build';
        } else if (this.level < this.upgradecosts.length) {
            document.getElementById(this.buildingname + '_upgrade').innerHTML = 'Upgrade';
        } else {
            document.getElementById(this.buildingname + '_upgrade').style.width = '0px';
            document.getElementById(this.buildingname + '_upgrade').style.border = '0px';
            document.getElementById(this.buildingname + '_upgrade').innerHTML = '';
            document.getElementById(this.buildingname + '_upgradecosts').style.width = 'calc(40vw - 0px)';
            document.getElementById(this.buildingname + '_upgradecosts').style.marginRight = '0px';
        }
        var costsstring = ''
        if(this.level < this.upgradecosts.length){
            for (var x = 0; x < this.upgradecosts[0].length; x++){
                if(this.upgradecosts[this.level][x] != 0){
                    if (typeof ressources != "undefined"){
                        if(this.upgradecosts[this.level][x] > ressources[x].amount){
                            costsstring = costsstring + '<span class="res_icon_inline"><b>' + this.upgradecosts[this.level][x] + '</b> ' +  ressources[x].printIcon(20) + '</span> ';
                        } else {
                            costsstring = costsstring + '<span style="color: grey" class="res_icon_inline"><b>' + this.upgradecosts[this.level][x] + '</b> ' +  ressources[x].printIcon(20) + '</span> ';
                        }
                    }
                }
            }
            if(this.checkUpgradeRequirements() == false) {
                costsstring = costsstring + '<span class="res_icon_inline">' + this.upgradeRequirements + '</span>';
                if(this.level == 0){
                        document.getElementById(this.name + 'bar_wrapper').style.display = 'none';
                        if(!document.getElementById('pasbuildings_wrapper').contains(document.getElementById(this.buildingname + '_wrapper'))){
                            document.getElementById('pasbuildings_wrapper').appendChild(document.getElementById(this.buildingname + '_wrapper'));
                        }
                    } else {
                        document.getElementById(this.name + 'bar_wrapper').style.display = 'block';
                        if(!document.getElementById('buildings_wrapper').contains(document.getElementById(this.buildingname + '_wrapper'))){
                            document.getElementById('buildings_wrapper').appendChild(document.getElementById(this.buildingname + '_wrapper'));
                        }
                    }
            } else {
                if(this.level == 0){
                    document.getElementById(this.name + 'bar_wrapper').style.display = 'none';
                    if(!document.getElementById('buildings_wrapper').contains(document.getElementById(this.buildingname + '_wrapper'))){
                            document.getElementById('buildings_wrapper').appendChild(document.getElementById(this.buildingname + '_wrapper'));
                        }
                } else {
                    document.getElementById(this.name + 'bar_wrapper').style.display = 'block';
                    if(!document.getElementById('buildings_wrapper').contains(document.getElementById(this.buildingname + '_wrapper'))){
                            document.getElementById('buildings_wrapper').appendChild(document.getElementById(this.buildingname + '_wrapper'));
                        }
                }
            }
            if (costsstring == '') {
                costsstring = '<span class="res_icon_inline">ready</span>';
            }
        } else {
            document.getElementById(this.name + 'bar_wrapper').style.display = 'block';
            if(!document.getElementById('buildings_wrapper').contains(document.getElementById(this.buildingname + '_wrapper'))){
                            document.getElementById('buildings_wrapper').appendChild(document.getElementById(this.buildingname + '_wrapper'));
            }
            costsstring = '<span class="res_icon_inline">Maximum LV</span>';
        }
            document.getElementById(this.buildingname + '_upgradecosts').innerHTML = costsstring;
        var ok = true;
        if(window.ressources){
            if(this.level < this.upgradecosts.length){
                for(var x = 0; x< ressources.length;x++){
                    if (this.upgradecosts[this.level][x] > ressources[x].amount || this.checkUpgradeRequirements() == false){
                        ok = false;
                    }
                }
                if(ok == true) {
                    document.getElementById(this.buildingname + '_upgradecosts').style.color = 'black';
                } else {
                    document.getElementById(this.buildingname + '_upgradecosts').style.color = 'red';
                }
            } else {
                document.getElementById(this.buildingname + '_upgradecosts').style.color = 'black';
            }
        }
    if(recalc){
            this.recalc();
        }
    
    },
    init : function(){
        this.refresh(false);
        for(var t=0; t<this.upgradecosts.length;t++){
            
            var s = ressources_names.length - this.upgradecosts[t].length;
            
            if (this.upgradecosts[t].length < ressources_names.length){
                for (var i=0; i<s; i++) {
                    this.upgradecosts[t].push(0);
                  }
            }
        }
    },
    pause : function(){
        if(this.bar.pause == true && this.bar.finishedState == true){
            this.bar.pause = false;
            this.lastupdate = Date.now();
            this.bar.finishedState = false;
            this.bar.setVal(0);
            document.getElementById(this.buildingname + '_name').innerHTML = this.buildingname.charAt(0).toUpperCase() + this.buildingname.slice(1);
            document.getElementById(this.buildingname + '_name').style.color = 'black';
        } else if(this.bar.pause == false) {
            this.bar.pause = true;
            document.getElementById(this.buildingname + '_name').innerHTML = this.buildingname.charAt(0).toUpperCase() + this.buildingname.slice(1) + ' (Pausiert)';
            document.getElementById(this.buildingname + '_name').style.color = 'grey';
        }
    },
    upgrade : function(){
        var ok = true;
        for(var x = 0; x< ressources.length;x++){
            if (this.upgradecosts[this.level][x] > ressources[x].amount){
                ok = false;
            }
        }
        if(this.checkUpgradeRequirements()== false) {
            ok = false;
        }
        if(ok == true) {
            if(this.level == 0){
                this.lastupdate = Date.now();
            }
            this.level++;
            this.bar.setSpeed(this.speedlevels[this.level]);
            
            for(x = 0; x< ressources.length; x++){  
                ressources[x].amount -= this.upgradecosts[this.level-1][x];
                if(ressources[x]!=this){
                    ressources[x].refresh(false);
                }
            }
            this.refresh(false);
            for( var x = 0 ; x < doctrines.length ; x++){
                doctrines[x].init();
            }
        }
    }
    }
    res.init();
    return res;
}

document.addEventListener('visibilitychange', function(ev) {
        if(document.visibilityState=='visible' && window.ressources){
            for( var x = 0 ; x < ressources.length ; x++){
              ressources[x].refresh(true);
            }
        }
});

buildFrontend(ressources_names,ressourcebuildings_names,productivity);

//std_speedlevels *= 2;


holz = newRessource(ressources_names[0],ressourcebuildings_names[0],100,0,std_speedlevels,[[5,0,0,0,0],[15,0,15,0,0,0,0,0,0,5,0]]);
holz.bar = newLoadBar(holz,ressources_names[0] + 'bar',0,100,0.0001,'std holzbar','7,255,0');

bretter = newRessource(ressources_names[1],ressourcebuildings_names[1],0,0,std_speedlevels,[[30,0,5,0,0]]);
bretter.bar = newLoadBar(bretter,ressources_names[1] + 'bar',0,100,0.000001,'std','7,255,0');
bretter.bar.finished = function() {
            this.parent.amount++;
            this.parent.lastupdate = Date.now();
            for( var x = 0 ; x < doctrines.length ; x++){
                doctrines[x].refresh();
            }
            
            this.parent.refresh(false);
            for( var x = 0 ; x < ressources.length ; x++){
                if(this.parent != ressources[x]){
                    ressources[x].refresh(false);
                }
            }

            var cOffset = offset(document.getElementById('loadbar_border_' + this.name));
            var style = window.getComputedStyle(document.getElementById('loadbar_border_' + this.name));
            var posx = cOffset.left;
            var posy = cOffset.top;
            var dimx = style.getPropertyValue("width");
            var dimy = style.getPropertyValue("height");
            var posArray = ['calc(' + posx +  'px + ' + dimx + ' + ' + '10px)','calc(' + posy +  'px + ' + dimy + ' - ' + '40px)'];
            castMovingIcon(this.name + '.png',posArray,'top',1,true,30);
            if(this.pause == false){
                this.finishedState = false;
                this.setVal(0);
                //this.timer = setTimeout(this.finished.bind(this), ((1000*(100))/this.speed));
                return true;
            } else {
                this.finishedState = true;
                this.div_bar.classList.remove(...this.div_bar.classList);
                this.div_bar.style.transitionDuration = '0s';
                this.div_bar.classList.add('bar_0');
            } 
        }


bretter.checkUpgradeRequirements = function(){
    if (bretter.level == 0){
        bretter.upgradeRequirements = holz.printBuildingIcon(20) + ' 2+';
        if (holz.level > 1) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return true;
    }
}

stein = newRessource(ressources_names[2],ressourcebuildings_names[2],50,0,std_speedlevels,[[10,0,0,0,0],[15,0,15,0,0,0,0,0,0,5,0]]);
stein.bar = newLoadBar(stein,ressources_names[2] + 'bar',0,100,0.000001,'std','7,255,0');

ziegel = newRessource(ressources_names[3],ressourcebuildings_names[3],0,0,std_speedlevels,[[20,30,2,0,20]]);
ziegel.bar = newLoadBar(ziegel,ressources_names[3] + 'bar',0,100,0.000001,'std','7,255,0');

ziegel.bar.finished = function() {
        }

ziegel.checkUpgradeRequirements = function(){
    if (ziegel.level == 0){
        ziegel.upgradeRequirements = stein.printBuildingIcon(20) + ' 2+';
        if (stein.level > 1) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return true;
    }
}

getreide = newRessource(ressources_names[4],ressourcebuildings_names[4],0,0,std_speedlevels,[[5,0,5,0,0]]);
getreide.bar = newLoadBar(getreide,ressources_names[4] + 'bar',0,100,0.000001,'std','7,255,0');

mehl = newRessource(ressources_names[5],ressourcebuildings_names[5],0,0,std_speedlevels,[[30,0,5,0,0]]);
mehl.bar = newLoadBar(mehl,ressources_names[5] + 'bar',0,100,0.000001,'std','7,255,0');

mehl.checkUpgradeRequirements = function(){
    if (mehl.level == 0){
        mehl.upgradeRequirements = getreide.printBuildingIcon(20) + ' 2+';
        if (getreide.level > 1) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return true;
    }
}

brot = newRessource(ressources_names[6],ressourcebuildings_names[6],10,0,std_speedlevels,[[30,0,5,0,0]]);
brot.bar = newLoadBar(brot,ressources_names[6] + 'bar',0,100,0.000001,'std','7,255,0');

brot.checkUpgradeRequirements = function(){
    if (brot.level == 0){
        brot.upgradeRequirements = mehl.printBuildingIcon(20) + ' 2+';
        if (mehl.level > 1) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return true;
    }
}

eisenerz = newRessource(ressources_names[7],ressourcebuildings_names[7],0,0,std_speedlevels,[[30,0,5,0,0]]);
eisenerz.bar = newLoadBar(eisenerz,ressources_names[7] + 'bar',0,100,0.000001,'std','7,255,0');

eisenerz.checkUpgradeRequirements = function(){
    if (eisenerz.level == 0){
        eisenerz.upgradeRequirements = stein.printBuildingIcon(20) + ' 4+';
        if (stein.level > 3) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return true;
    }
}

eisen = newRessource(ressources_names[8],ressourcebuildings_names[8],0,0,std_speedlevels,[[30,0,5,0,0]]);
eisen.bar = newLoadBar(eisen,ressources_names[8] + 'bar',0,100,0.000001,'std','7,255,0');

eisen.checkUpgradeRequirements = function(){
    if (eisen.level == 0){
        eisen.upgradeRequirements = eisenerz.printBuildingIcon(20) + ' 2+';
        if (eisenerz.level > 1) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return true;
    }
}

fleisch = newRessource(ressources_names[9],ressourcebuildings_names[9],50,0,std_speedlevels,[[20,0,5,0,0]]);
fleisch.bar = newLoadBar(fleisch,ressources_names[9] + 'bar',0,100,0.000001,'std','7,255,0');

leder = newRessource(ressources_names[10],ressourcebuildings_names[10],0,0,std_speedlevels,[[30,0,5,0,0]]);
leder.bar = newLoadBar(leder,ressources_names[10] + 'bar',0,100,0.000001,'std','7,255,0');

leder.checkUpgradeRequirements = function(){
    if (leder.level == 0){
        leder.upgradeRequirements = fleisch.printBuildingIcon(20) + ' 2+';
        if (fleisch.level > 1) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return true;
    }
}

ressources = [holz,bretter,stein,ziegel,getreide,mehl,brot,eisenerz,eisen,fleisch,leder];

for( var x = 0 ; x < ressources.length ; x++){
    ressources[x].refresh(false);
}

//Doctrines

eifer = newDoctrine('eifer','Erhöht deine Produktivität für 5 Sekunden um 500%. (CD: 30s)',[0,0,0,0,0,0,0,0,0,5,0],30,0,5);

eifer.beginEffect= function(){
    for( var x = 0 ; x < ressources.length ; x++){
        ressources[x].bar.setSpeed(ressources[x].bar.speed * 2);
    }
}

eifer.endEffect= function(){
    for( var x = 0 ; x < ressources.length ; x++){
        ressources[x].bar.setSpeed(ressources[x].bar.speed / 2);
    }
}

eifer.checkUpgradeRequirements = function(){
        eifer.upgradeRequirements = fleisch.printBuildingIcon(20) + ' 1+';
        if (fleisch.level > 0) {
            return true;
        } else {
            return false;
        }
   
}

doctrines = [eifer];
for( var x = 0 ; x < doctrines.length ; x++){
    doctrines[x].refresh();
}

</script>