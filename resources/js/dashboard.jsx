import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { Line } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

function Dashboard() {
  const [showAddMember, setShowAddMember] = useState(false);
  const notifications = [
    { id: 1, type: 'message', text: 'New message from Pastor John', time: '2m ago' },
    { id: 2, type: 'event', text: 'Event "Youth Fellowship" starts in 1 hour', time: '10m ago' },
    { id: 3, type: 'prayer', text: 'New prayer request submitted', time: '30m ago' },
  ];

  // Mock stats
  const stats = [
    { label: 'Total Members', value: 124, icon: 'fa-users', color: '#2563eb' },
    { label: 'Total Events', value: 18, icon: 'fa-calendar', color: '#22c55e' },
    { label: 'Attendance Today', value: 87, icon: 'fa-clipboard-check', color: '#06b6d4' },
    { label: 'Pending Prayers', value: 5, icon: 'fa-praying-hands', color: '#facc15', iconColor: '#b45309' },
  ];

  // Mock chart data
  const attendanceData = {
    labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    datasets: [
      {
        label: 'Attendance',
        data: [80, 82, 78, 90, 85, 88, 87],
        borderColor: '#2563eb',
        backgroundColor: 'rgba(37,99,235,0.1)',
        tension: 0.4,
        fill: true,
      },
    ],
  };
  const attendanceOptions = {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: { display: true, text: 'Attendance Trend (This Week)' },
    },
    scales: {
      y: { beginAtZero: true, ticks: { stepSize: 10 } },
    },
  };

  const handleAddMember = (e) => {
    e.preventDefault();
    alert('Member added! (Demo)');
    setShowAddMember(false);
  };

  const actionIcons = {
    member: 'fa-user-plus',
    attendance: 'fa-clipboard-check',
    message: 'fa-paper-plane',
    event: 'fa-calendar-plus',
  };

  // Custom styles for stat cards and quick actions
  const statCardStyle = color => ({
    background: color,
    color: '#fff',
    borderRadius: 12,
    boxShadow: '0 2px 8px rgba(0,0,0,0.07)',
    minWidth: 180,
    minHeight: 120,
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    fontWeight: 700,
    fontSize: 18,
  });
  const quickActionStyle = color => ({
    background: color,
    color: '#fff',
    border: 'none',
    borderRadius: 10,
    minHeight: 80,
    fontWeight: 600,
    fontSize: 18,
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    boxShadow: '0 2px 8px rgba(0,0,0,0.07)',
    transition: 'transform 0.1s',
  });

  return (
    <div className="d-flex flex-column align-items-center w-100" style={{ background: '#fff', minHeight: '100vh' }}>
      <div style={{ maxWidth: 900, width: '100%' }} className="mx-auto my-4">
        {/* Header */}
        <div className="text-center mb-3">
          <span style={{ fontWeight: 700, fontSize: 28, letterSpacing: 1 }}>Church Management Dashboard</span>
          <div className="text-muted" style={{ fontSize: 16 }}>Welcome back, Admin!</div>
        </div>
        {/* Stat Cards */}
        <div className="d-flex justify-content-between gap-3 mb-4 flex-wrap">
          {stats.map((stat, idx) => (
            <div key={stat.label} style={statCardStyle(stat.color)} className="flex-grow-1 mb-2 text-center">
              <i className={`fa ${stat.icon} mb-2`} style={{ fontSize: 32, color: stat.iconColor || '#fff' }}></i>
              <div style={{ fontSize: 32, fontWeight: 900 }}>{stat.value}</div>
              <div style={{ fontSize: 16, fontWeight: 500 }}>{stat.label}</div>
            </div>
          ))}
        </div>
        {/* Chart Card */}
        <div className="card mb-4" style={{ borderRadius: 16, boxShadow: '0 2px 8px rgba(0,0,0,0.07)' }}>
          <div className="card-body">
            <Line data={attendanceData} options={attendanceOptions} height={80} />
          </div>
        </div>
        {/* Quick Actions Card */}
        <div className="card mb-4" style={{ borderRadius: 16, boxShadow: '0 2px 8px rgba(0,0,0,0.07)' }}>
          <div className="card-body">
            <div className="mb-3" style={{ fontWeight: 600, fontSize: 20 }}>Quick Actions</div>
            <div className="d-flex gap-3 flex-wrap justify-content-between">
              <button style={quickActionStyle('#2563eb')} className="flex-grow-1 mb-2" onClick={() => setShowAddMember(true)}>
                <i className={`fa ${actionIcons.member} mb-2`} style={{ fontSize: 28 }}></i>
                Add Member
              </button>
              <button style={quickActionStyle('#22c55e')} className="flex-grow-1 mb-2">
                <i className={`fa ${actionIcons.attendance} mb-2`} style={{ fontSize: 28 }}></i>
                Mark Attendance
              </button>
              <button style={quickActionStyle('#06b6d4')} className="flex-grow-1 mb-2">
                <i className={`fa ${actionIcons.message} mb-2`} style={{ fontSize: 28 }}></i>
                Send Message
              </button>
              <button style={quickActionStyle('#facc15')} className="flex-grow-1 mb-2">
                <i className={`fa ${actionIcons.event} mb-2`} style={{ fontSize: 28, color: '#b45309' }}></i>
                Add Event
              </button>
            </div>
          </div>
        </div>
        {/* Notifications Card */}
        <div className="card mb-4" style={{ borderRadius: 16, boxShadow: '0 2px 8px rgba(0,0,0,0.07)' }}>
          <div className="card-body">
            <div className="mb-3" style={{ fontWeight: 600, fontSize: 20 }}>Notifications</div>
            <ul className="list-group list-group-flush">
              {notifications.map(n => (
                <li key={n.id} className="list-group-item d-flex justify-content-between align-items-center px-0" style={{ border: 'none', background: 'transparent' }}>
                  <span>
                    {n.type === 'message' && <span className="me-2" style={{ color: '#2563eb' }}><i className="fa fa-envelope"></i></span>}
                    {n.type === 'event' && <span className="me-2" style={{ color: '#22c55e' }}><i className="fa fa-calendar"></i></span>}
                    {n.type === 'prayer' && <span className="me-2" style={{ color: '#facc15' }}><i className="fa fa-praying-hands"></i></span>}
                    {n.text}
                  </span>
                  <span className="text-muted small">{n.time}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>
        {/* Add Member Modal */}
        {showAddMember && (
          <div className="modal fade show d-block" tabIndex="-1" style={{ background: 'rgba(0,0,0,0.5)' }}>
            <div className="modal-dialog">
              <div className="modal-content">
                <div className="modal-header">
                  <h5 className="modal-title">Add Member</h5>
                  <button type="button" className="btn-close" onClick={() => setShowAddMember(false)}></button>
                </div>
                <form onSubmit={handleAddMember}>
                  <div className="modal-body">
                    <div className="mb-3">
                      <label className="form-label">Name</label>
                      <input type="text" className="form-control" required />
                    </div>
                    <div className="mb-3">
                      <label className="form-label">Email</label>
                      <input type="email" className="form-control" required />
                    </div>
                  </div>
                  <div className="modal-footer">
                    <button type="button" className="btn btn-secondary" onClick={() => setShowAddMember(false)}>Cancel</button>
                    <button type="submit" className="btn btn-primary">Add</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

const container = document.getElementById('react-dashboard-root');
if (container) {
  createRoot(container).render(<Dashboard />);
} 