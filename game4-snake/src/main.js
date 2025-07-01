import "./style.css";
import { getRandomInt } from "./utils";

let canvas = document.getElementById('terrain');
let ctx = canvas.getContext('2d');

function goFullScreen() {
    const canvas = document.getElementById('terrain');
    
    if (canvas.requestFullscreen) {
      canvas.requestFullscreen();
    } else if (canvas.mozRequestFullScreen) { 
      canvas.mozRequestFullScreen();
    } else if (canvas.webkitRequestFullscreen) { 
      canvas.webkitRequestFullscreen();
    } else if (canvas.msRequestFullscreen) { 
      canvas.msRequestFullscreen();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'f' || event.key === 'F') { 
        goFullScreen();
    }
});

class Terrain {
    constructor(largeur, hauteur, ctx, nbPommes, difficulte) {
        this.largeur = largeur;
        this.hauteur = hauteur;
        this.ctx = ctx;
        this.nbPommes = nbPommes;
        this.difficulte = difficulte;
        this.init_sol();
    }

    init_sol() {
        this.sol = [];
        let pommesAPlacer = this.nbPommes;
        for (let i = 0; i < this.largeur; i++) {
            this.sol[i] = [];
            for (let j = 0; j < this.hauteur; j++) {
                if (i === 0 || j === 0 || i === this.largeur - 1 || j === this.hauteur - 1) {
                    this.sol[i][j] = 2;
                } else if (getRandomInt(this.difficulte) < 5) { // 1 chance sur 20
                    if (((i==10)&&(j=10))||((i==10)&&(j=9))||((i==10)&&(j=8))){
                        this.sol[i][j] = 0;
                    } else {
                        this.sol[i][j] = 1;
                    }
                } else {
                    if ((pommesAPlacer!=0)&&(getRandomInt(5)==1)){
                        this.sol[i][j] = 3;
                        pommesAPlacer--;
                    } else{
                        this.sol[i][j] = 0;
                    }
                }
            }
            }
        }

    draw() {
        for (let i = 0; i < this.largeur; i++) {
            for (let j = 0; j < this.hauteur; j++) {
                const code = this.sol[i][j];

                switch (code) {
                    case 0:
                        ctx.fillStyle = '#ffffff'; 
                        break;
                    case 1:
                        ctx.fillStyle = '#222222'; 
                        break;
                    case 2:
                        ctx.fillStyle = "black";
                        break;
                    case 3:
                        ctx.fillStyle = "red";
                        break;
                }
                ctx.fillRect(i * 20, j * 20, 20, 20);
                ctx.strokeStyle = '#cccccc';
                ctx.strokeRect(i * 20, j * 20, 20, 20);
            }
        }
    }

    read(i, j) {
        return this.sol[i][j];
    }
    
    write(i, j, val) {
        this.sol[i][j] = val;
    }

    add_pomme(){
        let tab_pomme = [];
        for (let k = 0; k < this.largeur; k++) {
            for (let l = 0; l < this.hauteur; l++) {
                if (this.sol[k][l] === 0) { 
                    tab_pomme.push([k, l]);
                } 
            }
        }
        let random = tab_pomme[getRandomInt(tab_pomme.length)]; 
        this.sol[random[0]][random[1]] = 3; 
    }
}

class Anneau {
    constructor(ctx, i, j, couleur, terrain) {
        this.ctx = ctx;
        this.i = i;
        this.j = j;
        this.couleur = couleur;
        this.terrain = terrain;
    }

    draw() {      
        this.ctx.fillStyle = this.couleur;
        this.ctx.fillRect(this.i * 20, this.j * 20, 20, 20);
        this.ctx.strokeStyle = '#cccccc';
        this.ctx.strokeRect(this.i * 20, this.j * 20, 20, 20);
    }

    move(d) {
        if (d === 0) this.j = (this.j - 1 + HAUTEUR) % HAUTEUR;
        else if (d === 1) this.i = (this.i + 1) % LARGEUR;
        else if (d === 2) this.j = (this.j + 1) % HAUTEUR;
        else if (d === 3) this.i = (this.i - 1 + LARGEUR) % LARGEUR;
        this.draw();
    }
    
    copy(a) {
        this.i = a.i;
        this.j = a.j;
    }

    read(direction) {
        if (direction === 0) return this.terrain.read(this.i, (this.j - 1 + HAUTEUR) % HAUTEUR);
        else if (direction === 1) return this.terrain.read((this.i + 1) % LARGEUR, this.j);
        else if (direction === 2) return this.terrain.read(this.i, (this.j + 1) % HAUTEUR);
        else if (direction === 3) return this.terrain.read((this.i - 1 + LARGEUR) % LARGEUR, this.j);
    }
}

class Serpent {
    constructor(l, i, j, d, terrain, controled) {
        this.l = l;
        this.d = d;
        this.body = [];
        this.terrain = terrain;
        this.controled = controled;
        this.body[0] = new Anneau(ctx, i, j, "yellow", this.terrain);
        if (d==0){
            for (let k = 1; k < l; k++) {
                this.body[k] = new Anneau(ctx, i, j, "green", this.terrain);
            }
        
        } else {
                for (let k = 1; k < l; k++) {
                    this.body[k] = new Anneau(ctx, i, j, "blue", this.terrain);
                }
        }
        
    }

    draw() {
        for (const anneau of this.body) {
            anneau.draw();
        }
    }

    move() {
        let future = this.body[0].read(this.d);
        if (future === 1 || future === 2 || future === 3) {
            this.d = (this.d + 1) % 4;
        } else {
            this.terrain.write(this.body[this.body.length - 1].i, this.body[this.body.length - 1].j, 0);
            for (let k = this.l - 1; k > 0; k--) {
                this.body[k].copy(this.body[k - 1]);
            }
            this.body[0].move(this.d);
            this.terrain.write(this.body[0].i, this.body[0].j, 2);
        }
    }

    extend() {
        this.body.push(new Anneau(ctx, this.body[this.l - 1].i, this.body[this.l - 1].j, this.couleur));
        this.l++;
    }

    move_guide() {
        let future = this.body[0].read(this.d);
        if (future==3){
            this.extend();
            this.terrain.add_pomme();
        }
        else if(future==1||future==2){
            return "Game Over";
        }
        this.terrain.write(this.body[this.body.length - 1].i, this.body[this.body.length - 1].j, 0);
        for (let k = this.l - 1; k > 0; k--) {
            this.body[k].copy(this.body[k - 1]);
        }
        this.body[0].move(this.d);
        this.terrain.write(this.body[0].i, this.body[0].j, 2);
    }

    move_aventure() {
        let future = this.body[0].read(this.d);
        if (future==3){
            this.extend();
            this.terrain.nbPommes-=1;
            if (this.terrain.nbPommes==0){
                return "Victoire";
            }
        }
        else if(future==1||future==2){
            return "Game Over";
        }
        this.terrain.write(this.body[this.body.length - 1].i, this.body[this.body.length - 1].j, 0);
        for (let k = this.l - 1; k > 0; k--) {
            this.body[k].copy(this.body[k - 1]);
        }
        this.body[0].move(this.d);
        this.terrain.write(this.body[0].i, this.body[0].j, 2);
    }
}

const score = document.createElement("h2");
score.textContent = "Sélectionnez un mode de jeu !";
document.body.appendChild(score);

const LARGEUR = 20; 
const HAUTEUR = 20;
const FACILE = 100;
const NORMAL = 90;
const DIFFICILE = 80;
const NBPOMMES = 3;

const TerrainSurvivant = new Terrain(LARGEUR, HAUTEUR, ctx, 1, NORMAL);
const TerrainAventure = new Terrain(LARGEUR, HAUTEUR, ctx, NBPOMMES, FACILE);

const NB_SERPENT = 3;
const Serpents = [
    new Serpent(3, 10, 10, 0, TerrainSurvivant),
    new Serpent(3, 15, 15, 2, TerrainSurvivant),
    new Serpent(3, 5, 5, 3, TerrainSurvivant)
];

let currentDirection = Serpents[0].d;

function handleKeyDown(event) {
    if ((event.key === "ArrowUp" || event.key === "z") && currentDirection !== 2) currentDirection = 0;
    else if ((event.key === "ArrowRight" || event.key === "d") && currentDirection !== 3) currentDirection = 1;
    else if ((event.key === "ArrowDown" || event.key === "s") && currentDirection !== 0) currentDirection = 2;
    else if ((event.key === "ArrowLeft" || event.key === "q") && currentDirection !== 1) currentDirection = 3;
}

document.addEventListener('keydown', handleKeyDown);

let animationTimer = 0;
let starttime = 0;
const maxfps = 5;
const interval = 1000 / maxfps;

function resetGame(terrain){
    if (terrain==TerrainAventure){
        terrain.nbPommes = NBPOMMES;
        terrain.difficulte = FACILE;
    }
    terrain.init_sol();
    terrain.draw();
    Serpents.length = 0;
    Serpents.push(new Serpent(3, 10, 10, 0, terrain));
    Serpents.push(new Serpent(3, 15, 15, 2, terrain));
    Serpents.push(new Serpent(3, 5, 5, 3, terrain));
    currentDirection = Serpents[0].d;
    starttime = 0; 
    const btnReplay = document.getElementById("Replay");
    if (btnReplay) {   
            btnReplay.remove();
    }
}

function niveauSup(terrain){
    if (terrain.difficulte === FACILE) {
        terrain.difficulte = NORMAL;
    }
    else if (terrain.difficulte === NORMAL) {
        terrain.difficulte = DIFFICILE;
    }
    else if (terrain.difficulte === DIFFICILE) {
        return ;
    }
    terrain.init_sol();
    Serpents[1].extend();
    Serpents[2].extend();
    terrain.nbPommes = NBPOMMES+1;
    for (let i = terrain.nbPommes; i>0; i--){
        terrain.add_pomme();
    }
    terrain.draw();
}

function snakeSurvivant(timestamp = 0){
    let game = "en cours";
    if (starttime === 0) starttime = timestamp;
        let delta = timestamp - starttime;
    if (delta >= interval) {
        for (let i = 1; i < NB_SERPENT; i++) {
            if (getRandomInt(10)<2){
                if ((Serpents[i].d==0)||(Serpents[i].d==2)){
                    Serpents[i].d = Math.floor(Math.random() * 2) * 2 + 1 // renvoie 1 ou 3
                }
                else if ((Serpents[i].d==1)||(Serpents[i].d==3)){
                    Serpents[i].d = Math.floor(Math.random() * 2) * 2 // renvoie 0 ou 2
                }
            }
            Serpents[i].move();
        }
        Serpents[0].d = currentDirection
        if (Serpents[0].move_guide()=="Game Over"){
            game = "fini";
        } 
        TerrainSurvivant.draw();
        for (let i = 0; i < NB_SERPENT; i++) {
            Serpents[i].draw();
        }
        score.textContent = "Score : " + (Serpents[0].body.length-3);
        starttime = timestamp - (delta % interval);

    }
    if (game == "en cours"){
        animationTimer = requestAnimationFrame(snakeSurvivant);
    } else{
        score.textContent = "Game Over ! Votre score était de " + (Serpents[0].body.length-3);
        cancelAnimationFrame(animationTimer);
        animationTimer = 0;
        const btnReplay = document.createElement("button");
        btnReplay.textContent = "Rejouer";
        btnReplay.id = "Replay"
        document.body.appendChild(btnReplay);
        btnReplay.addEventListener("click", () => {
            resetGame(TerrainSurvivant);
            requestAnimationFrame(snakeSurvivant); 
        });
    } 
}

function snakeAventure(timestamp = 0){
    let game = "en cours";
    if (starttime === 0) starttime = timestamp;
        let delta = timestamp - starttime;
    if (delta >= interval) {
        for (let i = 1; i < NB_SERPENT; i++) {
            if (getRandomInt(10)<2){
                if ((Serpents[i].d==0)||(Serpents[i].d==2)){
                    Serpents[i].d = Math.floor(Math.random() * 2) * 2 + 1 // renvoie 1 ou 3
                }
                else if ((Serpents[i].d==1)||(Serpents[i].d==3)){
                    Serpents[i].d = Math.floor(Math.random() * 2) * 2 // renvoie 0 ou 2
                }
            }
            Serpents[i].move();
        }
        Serpents[0].d = currentDirection
        let future = Serpents[0].move_aventure();
        if (future=="Game Over"){
            game = "fini";
        } 
        else if (future=="Victoire"){
            if (TerrainAventure.difficulte == DIFFICILE){
                game = "victoire"
            } else {
                niveauSup(TerrainAventure);
            }
        } 
        TerrainAventure.draw();
        for (let i = 0; i < NB_SERPENT; i++) {
            Serpents[i].draw();
        }
        if (TerrainAventure.difficulte==FACILE){
            score.textContent = "Niveau : 1";
        }
        else if (TerrainAventure.difficulte==NORMAL){
            score.textContent = "Niveau : 2";
        }
        else if (TerrainAventure.difficulte==DIFFICILE){
            score.textContent = "Niveau : 3";
        }
        starttime = timestamp - (delta % interval);

    }
    if (game == "en cours"){
        requestAnimationFrame(snakeAventure);
    } else if (game == "victoire"){
        score.textContent = "Victoire";
        cancelAnimationFrame(animationTimer);
        animationTimer = 0;
        const btnReplay = document.createElement("button");
        btnReplay.textContent = "Rejouer";
        btnReplay.id = "Replay"
        document.body.appendChild(btnReplay);
        btnReplay.addEventListener("click", () => {
            resetGame(TerrainAventure);
            requestAnimationFrame(snakeAventure); 
        });
    }
    else{
        if (TerrainAventure.difficulte==FACILE){
            score.textContent = "Game Over ! Vous avez atteinds le niveau 1";
        }
        else if (TerrainAventure.difficulte==NORMAL){
            score.textContent = "Game Over ! Vous avez atteinds le niveau 2";
        }
        else if (TerrainAventure.difficulte==DIFFICILE){
            score.textContent = "Game Over ! Vous avez atteinds le niveau 3";
        }        
        cancelAnimationFrame(animationTimer);
        animationTimer = 0;
        const btnReplay = document.createElement("button");
        btnReplay.textContent = "Rejouer";
        btnReplay.id = "Replay"
        document.body.appendChild(btnReplay);
        btnReplay.addEventListener("click", () => {
            resetGame(TerrainAventure);
            requestAnimationFrame(snakeAventure); 
        });
    } 
}

const btnSurvivant = document.getElementById("btnSurvivant");
btnSurvivant.addEventListener("click", function(){
    resetGame(TerrainSurvivant);
    requestAnimationFrame(snakeSurvivant);
});

const btnAventure = document.getElementById("btnAventure");
btnAventure.addEventListener("click", function(){
    resetGame(TerrainAventure);
    requestAnimationFrame(snakeAventure);
});
