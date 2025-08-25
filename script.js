document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const flowerPopup = null;
    const continueBtn = null;

    // Handle login form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if (!username || !password) {
            alert('Mohon isi username dan password!');
            return;
        }
        
        // Strict validation
        const isValid = username.toLowerCase() === 'talith' && password === '123';
        if (!isValid) {
            alert('Username atau password salah.');
            return;
        }
        
        // Langsung pindah ke halaman confess (tanpa popup bunga)
        window.location.href = 'confess.html';
    });

    // Popup bunga dihapus: tidak ada handler tambahan

    // Add some interactive effects
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Add extra sparkle effect to flower garden
    const flowerGarden = null;
    if (false) {
        setInterval(() => {
            const extraSparkle = document.createElement('div');
            extraSparkle.className = 'sparkle';
            extraSparkle.style.left = Math.random() * 100 + '%';
            extraSparkle.style.top = Math.random() * 100 + '%';
            extraSparkle.style.animationDuration = '1s';
            flowerGarden.appendChild(extraSparkle);
            
            setTimeout(() => {
                if (extraSparkle.parentNode) {
                    extraSparkle.parentNode.removeChild(extraSparkle);
                }
            }, 1000);
        }, 800);
    }

    // Render pixel flower only if popup in pure mode (avoid on login page)
    const pixelStage = null;
    if (false) {
        // Procedurally build a sakura tree map (64x40)
        function buildSakuraMap(cols=64, rows=40){
            const g = Array.from({length: rows}, ()=>Array(cols).fill('.'));
            const rand = (n)=>Math.random()*n;
            // trunk
            const tx = 10; // trunk x
            for(let y=rows-1; y>=rows-14; y--){
                g[y][tx] = 'S';
                if (y%2===0) g[y][tx+1] = 'S';
            }
            // branches up-right
            let bx = tx+1, by = rows-14;
            for(let i=0;i<10;i++){
                const ry = by - i;
                const cx1 = bx + i;
                const cx2 = bx + i + 1;
                if (g[ry] && cx1 >= 0 && cx1 < cols) g[ry][cx1] = 'O';
                if (i % 2 === 0 && g[ry] && cx2 >= 0 && cx2 < cols) g[ry][cx2] = 'O';
            }
            // canopy: slanted triangle with noise
            const top = 6, bottom = 18;
            for(let y=top; y<=bottom; y++){
                const start = Math.max(12, Math.floor(tx + (y-top)*0.8));
                const width = Math.min(cols-start-2, 34 + Math.floor((y-top)*2.2));
                for(let x=start; x<start+width; x++){
                    if (Math.random() < 0.06) continue; // holes
                    g[y][x] = Math.random()<0.25 ? 'p' : 'P';
                }
            }
            // drooping petals below canopy edge
            for(let y=bottom+1; y<bottom+4; y++){
                for(let x=tx+20; x<cols-6; x+=3){ if(Math.random()<0.4) g[y][x]='P'; }
            }
            return g.map(r=>r.join(''));
        }
        const pixelMap = buildSakuraMap();

        function drawPixelFlower() {
            pixelStage.innerHTML = '';
            pixelMap.forEach(row => {
                row.split('').forEach(ch => {
                    if (ch === '.') return;
                    const d = document.createElement('div');
                    d.className = 'px ' + (
                        ch==='P' ? 'P' :
                        ch==='p' ? 'pdk' :
                        ch==='C' ? 'C' :
                        ch==='c' ? 'cdk' :
                        ch==='S' ? 'S' :
                        ch==='L' ? 'L' : 'O');
                    pixelStage.appendChild(d);
                });
            });
        }
        drawPixelFlower();

        // Falling petals effect
        function spawnPetal(){
            const petal = document.createElement('div');
            petal.className = 'falling-petal';
            const vw = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            const startX = Math.random() * vw;
            const endX = startX + (Math.random()*120 - 60);
            const dur = 5000 + Math.random()*4000;
            petal.style.left = startX + 'px';
            petal.style.setProperty('--sx', '0px');
            petal.style.setProperty('--ex', (endX-startX)+'px');
            petal.style.animationDuration = dur + 'ms';
            document.body.appendChild(petal);
            setTimeout(()=>petal.remove(), dur+200);
        }
        setInterval(spawnPetal, 220);
    }
    // Set CSS custom properties for flower leaf rotations
    const flowers = document.querySelectorAll('.flower');
    flowers.forEach(flower => {
        const leaves = flower.querySelectorAll('.flower__leaf');
        leaves.forEach((leaf, index) => {
            const rotation = index * 60;
            leaf.style.setProperty('--rotation', rotation + 'deg');
        });
    });

    // Add loading animation
    const c = setTimeout(() => {
        document.body.classList.remove("not-loaded");
        clearTimeout(c);
    }, 1000);
}); 