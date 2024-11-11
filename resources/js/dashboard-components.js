class DashboardComponent {
  constructor(id, settings) {
    this.id = id;
    this.settings = settings;
    this.element = document.querySelector(`[data-id="component-${id}"]`);
    this.refreshUrl = this.element.dataset.refreshUrl;
    this.refreshInterval = parseInt(this.element.dataset.refreshInterval, 10);
    this.type = this.element.dataset.type;
    this.instance = null;
    this.isLoading = false;
    this.setupEventListeners();
  }

  init(data) {
    this.data = data;
    this.render();
    this.setupRefreshTimer();
  }

  render() {
    switch (this.type) {
      case 'chart':
        this.renderChart();
        break;
      case 'table':
        this.renderTable();
        break;
      case 'metric':
        this.renderMetric();
        break;
      case 'list':
        this.renderList();
        break;
    }
  }

  renderChart() {
    const canvas = this.element.querySelector('canvas');
    const ctx = canvas.getContext('2d');

    if (this.instance) {
      this.instance.destroy();
    }

    this.instance = new Chart(ctx, {
      type: this.settings.type,
      data: this.data,
      options: this.settings.options
    });
  }

  renderTable() {
    if (this.instance) {
      this.instance.destroy();
    }

    this.instance = new DataTable(this.element.querySelector('table'), {
      data: this.data.rows,
      columns: this.data.columns.map(col => ({
        title: col.label,
        data: col.name,
        sortable: col.sortable
      })),
      pageLength: this.settings.pagination?.perPage || 10,
      searching: this.settings.filtering?.enabled || false,
      ordering: this.settings.sorting?.enabled || false,
      responsive: true,
      dom: 'Bfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
  }

  renderMetric() {
    // Update the metric value
    const valueElement = this.element.querySelector('.metric-value h2');
    valueElement.textContent = this.data.value;
    valueElement.className = `mb-0 ${this.data.color ? 'text-' + this.data.color : ''}`;

    // Update comparison if exists
    if (this.data.comparison) {
      const comparisonElement = this.element.querySelector('.metric-change');
      if (comparisonElement) {
        const badge = comparisonElement.querySelector('.badge');
        badge.className = `badge bg-${this.data.comparison.direction === 'up' ? 'success' : 'danger'}`;
        badge.innerHTML = `
                    <i class="ri-arrow-${this.data.comparison.direction}-line"></i>
                    ${this.data.comparison.percentage.toFixed(1)}%
                `;
      }
    }

    // Render sparkline if exists
    if (this.data.sparkline && this.settings.trend?.showSparkline) {
      const sparklineElement = this.element.querySelector('.metric-sparkline');
      if (sparklineElement) {
        if (this.instance) {
          this.instance.destroy();
        }

        this.instance = new ApexCharts(sparklineElement, {
          chart: {
            type: 'line',
            sparkline: { enabled: true }
          },
          series: [
            {
              data: this.data.sparkline.points
            }
          ],
          stroke: { curve: 'smooth' },
          markers: { size: 0 },
          colors: [this.data.color || '#4CAF50']
        });
        this.instance.render();
      }
    }
  }

  renderList() {
    const container = this.element.querySelector('.list-container');

    // Setup drag and drop if enabled
    if (this.settings.display?.dragAndDrop) {
      this.instance = new Sortable(container, {
        animation: 150,
        handle: '.drag-handle',
        onEnd: evt => this.handleReorder(evt)
      });
    }

    // Setup animations if enabled
    if (this.settings.display?.animation) {
      AOS.init({
        duration: 800,
        offset: 20
      });
    }

    // Setup infinite scroll if enabled
    if (this.settings.pagination?.style === 'infinite-scroll') {
      this.setupInfiniteScroll();
    }
  }

  setupEventListeners() {
    // Refresh button
    const refreshBtn = this.element.querySelector('.refresh-btn');
    if (refreshBtn) {
      refreshBtn.addEventListener('click', () => this.refresh());
    }

    // Fullscreen button
    const fullscreenBtn = this.element.querySelector('[data-action="fullscreen"]');
    if (fullscreenBtn) {
      fullscreenBtn.addEventListener('click', e => {
        e.preventDefault();
        this.toggleFullscreen();
      });
    }

    // Export buttons
    const exportBtns = this.element.querySelectorAll('[data-action^="export-"]');
    exportBtns.forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        const format = e.target.dataset.action.split('-')[1];
        this.exportData(format);
      });
    });

    // List item actions
    const actionBtns = this.element.querySelectorAll('[data-action]');
    actionBtns.forEach(btn => {
      btn.addEventListener('click', e => {
        const action = e.target.dataset.action;
        const confirm = e.target.dataset.confirm;

        if (confirm && !window.confirm(confirm)) {
          return;
        }

        this.handleAction(action, e.target.closest('.list-item'));
      });
    });
  }

  setupRefreshTimer() {
    if (this.refreshInterval) {
      setInterval(() => this.refresh(), this.refreshInterval);
    }
  }

  setupInfiniteScroll() {
    const options = {
      root: null,
      rootMargin: '0px',
      threshold: 1.0
    };

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !this.isLoading) {
          this.loadMoreItems();
        }
      });
    }, options);

    const sentinel = document.createElement('div');
    sentinel.className = 'sentinel';
    this.element.appendChild(sentinel);
    observer.observe(sentinel);
  }

  async refresh() {
    if (this.isLoading) return;

    this.isLoading = true;
    this.element.classList.add('refreshing');

    try {
      const response = await fetch(this.refreshUrl);
      const result = await response.json();

      if (result.success) {
        this.data = result.data;
        this.render();
      } else {
        console.error('Failed to refresh component:', result.error);
      }
    } catch (error) {
      console.error('Error refreshing component:', error);
    } finally {
      this.isLoading = false;
      this.element.classList.remove('refreshing');
    }
  }

  toggleFullscreen() {
    this.element.classList.toggle('fullscreen');
    if (this.instance && typeof this.instance.resize === 'function') {
      this.instance.resize();
    }
  }

  async exportData(format) {
    // Implementation depends on component type
    switch (this.type) {
      case 'table':
        this.instance.button(`.buttons-${format}`).trigger();
        break;
      case 'chart':
        const canvas = this.element.querySelector('canvas');
        const link = document.createElement('a');
        link.download = `chart-${this.id}.${format}`;
        link.href = canvas.toDataURL(`image/${format}`);
        link.click();
        break;
    }
  }

  async handleAction(action, item) {
    // Implementation depends on action type
    console.log(`Handling action ${action} for item`, item);
  }

  async handleReorder(event) {
    const items = Array.from(event.target.children).map((el, index) => ({
      id: el.dataset.id,
      position: index
    }));

    try {
      const response = await fetch(this.refreshUrl.replace('refresh-data', 'reorder'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ items })
      });

      const result = await response.json();
      if (!result.success) {
        console.error('Failed to reorder items:', result.error);
      }
    } catch (error) {
      console.error('Error reordering items:', error);
    }
  }

  async loadMoreItems() {
    if (this.isLoading) return;

    this.isLoading = true;
    const currentPage = parseInt(this.element.dataset.currentPage, 10) || 1;

    try {
      const response = await fetch(`${this.refreshUrl}?page=${currentPage + 1}`);
      const result = await response.json();

      if (result.success) {
        const container = this.element.querySelector('.list-container');
        result.data.items.forEach(item => {
          const div = document.createElement('div');
          div.className = 'list-item';
          div.dataset.id = item.id;
          div.innerHTML = item.html;
          container.appendChild(div);
        });

        this.element.dataset.currentPage = currentPage + 1;
        if (!result.data.hasMorePages) {
          this.element.querySelector('.sentinel').remove();
        }
      }
    } catch (error) {
      console.error('Error loading more items:', error);
    } finally {
      this.isLoading = false;
    }
  }

  destroy() {
    if (this.instance && typeof this.instance.destroy === 'function') {
      this.instance.destroy();
    }
  }
}
