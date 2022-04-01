document.addEventListener('DOMContentLoaded', () => {
    const grid = document.querySelector('#tetris-bg')
    let squares = Array.from(document.querySelectorAll('#tetris-bg div'))
    const ScoreDisplay = document.querySelector('#score')
    const StartBtn = document.querySelector('#start-button')
    const replayBtn = document.querySelector("#replay")
    const width = 10
    let timerId
    let score=1

    const rPieces = [
        [0, width, width*2,1],
        [0, 1, 2, width+2],
        [1, width+1, width*2+1, width*2],
        [0, width, width+1, width+2]
    ]
    const lPieces = [
        [0, width, width*2, width*2+1],
        [width, width+1, width+2, 2],
        [0, 1, width+1, width*2+1],
        [0, 1, 2, width]
    ]
    const zPieces = [ 
        [0, width, width+1, width*2+1], 
        [width, width+1, 1, 2],
        [0, width, width+1, width*2+1], 
        [width, width+1, 1, 2]
    ]
    const sPieces = [
        [width*2, width, width+1, 1],
        [0, 1, width+1, width+2],
        [width*2, width, width+1, 1],
        [0, 1, width+1, width+2]
    ]
    const tPieces = [
        [0, 1, 2, width+1],
        [width, 1, width+1, width*2+1],
        [width, width+1, 1, width+2],
        [0, width, width*2, width+1]
    ]
    const oPieces = [
        [0,1,width,width+1],
        [0,1,width,width+1],
        [0,1,width,width+1],
        [0,1,width,width+1]
    ]
    const iPieces = [
        [0, width, width*2, width*3],
        [0,1,2,3],
        [0, width, width*2, width*3],
        [0,1,2,3]
    ]

    const pieces = [rPieces, lPieces, zPieces, sPieces, tPieces, oPieces, iPieces]

    let currentPosition = 4
    let currentRotation = 0

    let random = Math.floor(Math.random()*pieces.length)
    let currentBlock = pieces[random][currentRotation]

    function draw() {
        currentBlock.forEach(index => {
            if (random == 0){
                squares[currentPosition + index].classList.add('rpiece')
            }else if(random == 1){
                squares[currentPosition + index].classList.add('lpiece')
            }else if(random == 2){
                squares[currentPosition + index].classList.add('zpiece')
            }else if(random == 3){
                squares[currentPosition + index].classList.add('spiece')
            }else if(random == 4){
                squares[currentPosition + index].classList.add('tpiece')
            }else if(random == 5){
                squares[currentPosition + index].classList.add('opiece')
            }else if(random == 6){
                squares[currentPosition + index].classList.add('ipiece')
            }else {
                squares[currentPosition + index].classList.add('pieces')
            }
            
        })
    }
    function undraw() {
        currentBlock.forEach(index =>{
            if (random == 0){
                squares[currentPosition + index].classList.remove('rpiece')
            }else if(random == 1){
                squares[currentPosition + index].classList.remove('lpiece')
            }else if(random == 2){
                squares[currentPosition + index].classList.remove('zpiece')
            }else if(random == 3){
                squares[currentPosition + index].classList.remove('spiece')
            }else if(random == 4){
                squares[currentPosition + index].classList.remove('tpiece')
            }else if(random == 5){
                squares[currentPosition + index].classList.remove('opiece')
            }else if(random == 6){
                squares[currentPosition + index].classList.remove('ipiece')
            }else {
                squares[currentPosition + index].classList.remove('pieces')
            }
        })
    }

    function control(e){
        if(e.keyCode===37){
            moveLeft()
        }
        if(e.keyCode===38){
            rotateClockwise()
        }
        if(e.keyCode===39){
            moveRight()
        }
        if(e.keyCode===40){
            moveDown()
        }
        if(e.keyCode===68){
            rotateClockwise()
        }
        if(e.keyCode===65){
            rotateAnticlockwise()
        }
    }
    document.addEventListener('keydown', control)

    function moveDown() {
        if (timerId){
            freeze()
            undraw()
            currentPosition+= width
            draw()
        }
    }

    function freeze(){
        if (currentBlock.some(index => squares[currentPosition+index+width].classList.contains('taken'))){
            currentBlock.forEach(index => squares[currentPosition+index].classList.add('taken')) 
            
           random = Math.floor(Math.random()*pieces.length)
           currentBlock = pieces[random][currentRotation]
           currentPosition = 4
           score +=1
           ScoreDisplay.innerHTML = score
           addScore()
           gameOver()
           draw()
           
        }
    }

    function moveLeft(){
        if (timerId){
        undraw()
        const isAtLeftEdge = currentBlock.some(index => (currentPosition + index)% width===0)
    
        if (!isAtLeftEdge) currentPosition -=1
        if (currentBlock.some(index=>squares[currentPosition + index].classList.contains('taken'))){
            currentPosition += 1
        }
        draw()
    }}

    function moveRight() {
        if (timerId){
            undraw()
            const isAtRightEdge = currentBlock.some(index => (currentPosition+index)%width===width -1)
            if(!isAtRightEdge) currentPosition+=1
            if(currentBlock.some(index => squares[currentPosition + index].classList.contains('taken'))){
                currentPosition-=1
            }
            draw()
        }
    }
    function rotateClockwise(){
        if (timerId){
        undraw()
        currentRotation ++
        if(currentRotation === currentBlock.length){
            currentRotation = 0
        }
        currentBlock = pieces[random][currentRotation]
        if((currentBlock.some(index => (currentPosition+index)%width===width -1)) & (currentBlock.some(index => (currentPosition + index)% width===0))){
            while(currentBlock.some(index => (currentPosition + index)% width===0)){
                currentPosition-=1
            }
        }
        draw()
    }}
    function rotateAnticlockwise(){
        if (timerId){
        undraw()
        currentRotation --
        if(currentRotation === -1){
            currentRotation = 3
        }
        currentBlock = pieces[random][currentRotation]
        if((currentBlock.some(index => (currentPosition+index)%width===width -1)) & (currentBlock.some(index => (currentPosition + index)% width===0))){
            while(currentBlock.some(index => (currentPosition + index)% width===0)){
                currentPosition-=1
            }
        }
        draw() 
    }}
    StartBtn.addEventListener('click', () =>{
        
        if (timerId){
            clearInterval(timerId)
            timerId = null
        }else {
            
            buttonVanish()
            draw()
            timerId = setInterval(moveDown, 1000)
        }
    })
    replayBtn.addEventListener('click', () =>{
        if (timerId){
            clearInterval(timerId)
            timerId = null
        }else {
            
            draw()
            timerId = setInterval(moveDown,1000) 
        }
    })
    
    function addScore(){
        if(timerId){
            for (let i = 0; i<199;i+=width){
                const row = [i, i+1, i+2, i+3, i+4, i+5, i+6, i+7, i+8, i+9]
                if (row.every(index => squares[index].classList.contains('taken'))) {
                    score +=10
                    ScoreDisplay.innerHTML = score
                    row.forEach(index => {
                    squares[index].classList.remove('taken')
                    if (squares[index].classList.contains('rpiece')){
                        squares[index].classList.remove('rpiece')
                    }else if(squares[index].classList.contains('lpiece')){
                        squares[index].classList.remove('lpiece')
                    }else if(squares[index].classList.contains('zpiece')){
                        squares[index].classList.remove('zpiece')
                    }else if(squares[index].classList.contains('spiece')){
                        squares[index].classList.remove('spiece')
                    }else if(squares[index].classList.contains('tpiece')){
                        squares[index].classList.remove('tpiece')
                    }else if(squares[index].classList.contains('opiece')){
                        squares[index].classList.remove('opiece')
                    }else if(squares[index].classList.contains('ipiece')){
                        squares[index].classList.remove('ipiece')
                    }else {
                        squares[index].classList.remove('pieces')
                    } 
                    })
                    const squaresRemoved = squares.splice(i, width)
                    squares = squaresRemoved.concat(squares)
                    squares.forEach(cell => grid.appendChild(cell))
                }
            }
        }
    }
    function gameOver(){
        if (currentBlock.some(index => squares[currentPosition + index].classList.contains('taken'))){
            clearInterval(timerId)
            timerId=null
            pauseAudio()
            document.location = 'leaderboard.php?score='+ score
        }
    }
    function buttonVanish(){
        document.getElementById("start-button").style.visibility="hidden"
    }
    

})
var audio = new Audio("Music/tetris-gameboy-02.mp3");

function playAudio(){
    audio.play();
}

audio.loop = true;

function pauseAudio(){
    audio.pause();
}


