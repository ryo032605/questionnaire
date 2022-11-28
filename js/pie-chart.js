poll_chart();
function poll_chart() {
    const $chart = document.querySelector('#chart');

    if(!$chart) {
        return;
    }
    
    const ctx = $chart.getContext('2d');

    const likes = $chart.dataset.likes;
    const dislikes = $chart.dataset.dislikes;

    let data;

    if(likes == 0 && dislikes == 0) {
        data = {
            labels: ['まだ投票がありません。'],
            datasets: [{
                data: [1],
                backgroundColor: [
                    '#9ca3af'
                ]
            }]
        }
    } else {
        data = {
            labels: ['賛成', '反対'],
            datasets: [{
                data: [likes, dislikes],
                backgroundColor: [
                    '#34d399',
                    '#f87171'
                ]
            }]
        }
    }

    new Chart(ctx,  {
        type: 'pie',
        data: data,
        options: {
            legend: {
                position: 'bottom',
                labels: {
                    fontSize: 18
                }
            }
        }
    });
}