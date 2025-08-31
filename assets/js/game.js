// Nim Game JavaScript
class NimGame {
    constructor() {
        this.piles = [];
        this.currentPlayer = 'user'; // 'user' or 'computer'
        this.gameOver = false;
        this.moveCount = 0;
        
        this.gameBoard = document.getElementById('gameBoard');
        this.turnIndicator = document.getElementById('turnIndicator');
        this.gameMessage = document.getElementById('gameMessage');
        this.moveLog = document.getElementById('moveLog');
        
        this.initializeGame();
        this.setupEventListeners();
    }
    
    initializeGame() {
        this.piles = [];
        this.currentPlayer = 'user';
        this.gameOver = false;
        this.moveCount = 0;
        
        // Create 5 piles with random stones (1-10 each)
        for (let i = 0; i < 5; i++) {
            this.piles.push(Math.floor(Math.random() * 10) + 1);
        }
        
        this.renderBoard();
        this.updateTurnIndicator();
        this.clearMoveLog();
        this.addLogEntry('Game started! Click on stones to remove them.', 'system');
    }
    
    setupEventListeners() {
        document.getElementById('resetGame').addEventListener('click', () => {
            this.initializeGame();
        });
        
        document.getElementById('backToPortfolio').addEventListener('click', () => {
            window.location.href = 'Naquib.htm';
        });
    }
    
    renderBoard() {
        this.gameBoard.innerHTML = '';
        
        this.piles.forEach((stoneCount, pileIndex) => {
            const pileElement = document.createElement('div');
            pileElement.className = 'pile';
            pileElement.dataset.pileIndex = pileIndex;
            
            // Pile label
            const label = document.createElement('div');
            label.className = 'pile-label';
            label.textContent = `Pile ${pileIndex + 1} (${stoneCount})`;
            pileElement.appendChild(label);
            
            // Pile stones container
            const stonesContainer = document.createElement('div');
            stonesContainer.className = 'pile-stones';
            
            // Create stones
            for (let stoneIndex = 0; stoneIndex < stoneCount; stoneIndex++) {
                const stone = document.createElement('div');
                stone.className = 'stone';
                stone.dataset.pileIndex = pileIndex;
                stone.dataset.stoneIndex = stoneIndex;
                
                if (!this.gameOver && this.currentPlayer === 'user') {
                    stone.addEventListener('click', (e) => this.handleStoneClick(e));
                }
                
                stonesContainer.appendChild(stone);
            }
            
            pileElement.appendChild(stonesContainer);
            this.gameBoard.appendChild(pileElement);
        });
    }
    
    handleStoneClick(event) {
        if (this.gameOver || this.currentPlayer !== 'user') return;
        
        const pileIndex = parseInt(event.target.dataset.pileIndex);
        const stoneIndex = parseInt(event.target.dataset.stoneIndex);
        
        // Remove stones from this position to the top
        const stonesToRemove = this.piles[pileIndex] - stoneIndex;
        
        if (stonesToRemove > 0) {
            this.makeMove(pileIndex, stonesToRemove, 'user');
        }
    }
    
    makeMove(pileIndex, stonesToRemove, player) {
        if (this.gameOver) return;
        
        // Animate stone removal
        this.animateStoneRemoval(pileIndex, stonesToRemove, () => {
            // Update game state
            this.piles[pileIndex] -= stonesToRemove;
            this.moveCount++;
            
            // Log the move
            const playerName = player === 'user' ? 'You' : 'Computer';
            this.addLogEntry(
                `${playerName} removed ${stonesToRemove} stone${stonesToRemove > 1 ? 's' : ''} from Pile ${pileIndex + 1}`,
                player === 'user' ? 'user-move' : 'computer-move'
            );
            
            // Check for game end
            if (this.isGameOver()) {
                this.endGame(player);
                return;
            }
            
            // Switch players
            this.currentPlayer = player === 'user' ? 'computer' : 'user';
            this.updateTurnIndicator();
            
            // Re-render board
            this.renderBoard();
            
            // If it's computer's turn, make computer move
            if (this.currentPlayer === 'computer' && !this.gameOver) {
                setTimeout(() => {
                    this.makeComputerMove();
                }, 1000);
            }
        });
    }
    
    animateStoneRemoval(pileIndex, stonesToRemove, callback) {
        const pile = this.gameBoard.children[pileIndex];
        const stones = pile.querySelectorAll('.stone');
        const startIndex = this.piles[pileIndex] - stonesToRemove;
        
        let animationCount = 0;
        
        for (let i = startIndex; i < this.piles[pileIndex]; i++) {
            const stone = stones[i];
            if (stone) {
                stone.classList.add('removing');
                setTimeout(() => {
                    animationCount++;
                    if (animationCount === stonesToRemove) {
                        callback();
                    }
                }, 500);
            }
        }
        
        // Fallback in case animation doesn't trigger
        setTimeout(() => {
            if (animationCount < stonesToRemove) {
                callback();
            }
        }, 600);
    }
    
    makeComputerMove() {
        if (this.gameOver) return;
        
        const { pileIndex, stonesToRemove } = this.calculateOptimalMove();
        this.makeMove(pileIndex, stonesToRemove, 'computer');
    }
    
    calculateOptimalMove() {
        // Calculate nim-sum (XOR of all pile sizes)
        const nimSum = this.piles.reduce((xor, pile) => xor ^ pile, 0);
        
        if (nimSum === 0) {
            // Losing position, make any valid move
            for (let i = 0; i < this.piles.length; i++) {
                if (this.piles[i] > 0) {
                    return { pileIndex: i, stonesToRemove: 1 };
                }
            }
        }
        
        // Winning position, find the move that makes nim-sum 0
        for (let i = 0; i < this.piles.length; i++) {
            const targetSize = this.piles[i] ^ nimSum;
            if (targetSize < this.piles[i]) {
                return { 
                    pileIndex: i, 
                    stonesToRemove: this.piles[i] - targetSize 
                };
            }
        }
        
        // Fallback (shouldn't reach here)
        for (let i = 0; i < this.piles.length; i++) {
            if (this.piles[i] > 0) {
                return { pileIndex: i, stonesToRemove: 1 };
            }
        }
        
        return { pileIndex: 0, stonesToRemove: 1 };
    }
    
    isGameOver() {
        return this.piles.every(pile => pile === 0);
    }
    
    endGame(winner) {
        this.gameOver = true;
        
        if (winner === 'user') {
            this.gameMessage.innerHTML = '🎉 Congratulations! You won!';
            this.gameMessage.className = 'game-message winner-message';
            this.addLogEntry('🎉 You won the game!', 'user-move');
        } else {
            this.gameMessage.innerHTML = '💻 Computer wins! Better luck next time!';
            this.gameMessage.className = 'game-message loser-message';
            this.addLogEntry('💻 Computer won the game!', 'computer-move');
        }
        
        this.turnIndicator.textContent = 'Game Over';
        this.turnIndicator.style.animation = 'none';
    }
    
    updateTurnIndicator() {
        if (this.gameOver) return;
        
        if (this.currentPlayer === 'user') {
            this.turnIndicator.textContent = '👤 Your Turn';
            this.turnIndicator.style.background = 'rgba(76, 175, 80, 0.2)';
            this.turnIndicator.style.borderColor = '#4CAF50';
            document.body.classList.remove('computer-turn');
        } else {
            this.turnIndicator.textContent = '💻 Computer\'s Turn';
            this.turnIndicator.style.background = 'rgba(244, 67, 54, 0.2)';
            this.turnIndicator.style.borderColor = '#f44336';
            document.body.classList.add('computer-turn');
        }
        
        this.gameMessage.innerHTML = '';
        this.gameMessage.className = 'game-message';
    }
    
    addLogEntry(message, type = '') {
        const entry = document.createElement('p');
        entry.className = `log-entry ${type}`;
        entry.textContent = `[Move ${this.moveCount}] ${message}`;
        
        this.moveLog.appendChild(entry);
        this.moveLog.scrollTop = this.moveLog.scrollHeight;
    }
    
    clearMoveLog() {
        this.moveLog.innerHTML = '';
        this.moveCount = 0;
    }
}

// Initialize game when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new NimGame();
});

// Add some visual effects
document.addEventListener('DOMContentLoaded', () => {
    // Add particle effect for background
    const createParticle = () => {
        const particle = document.createElement('div');
        particle.style.position = 'fixed';
        particle.style.width = '4px';
        particle.style.height = '4px';
        particle.style.background = 'rgba(255, 215, 0, 0.6)';
        particle.style.borderRadius = '50%';
        particle.style.left = Math.random() * 100 + 'vw';
        particle.style.top = '100vh';
        particle.style.pointerEvents = 'none';
        particle.style.zIndex = '-1';
        
        document.body.appendChild(particle);
        
        const animation = particle.animate([
            { transform: 'translateY(0)', opacity: 1 },
            { transform: 'translateY(-100vh)', opacity: 0 }
        ], {
            duration: Math.random() * 3000 + 2000,
            easing: 'linear'
        });
        
        animation.addEventListener('finish', () => {
            particle.remove();
        });
    };
    
    // Create particles occasionally
    setInterval(createParticle, 500);
});
