<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Logs website page views into the visitors table: one row per IP per day, with a hit counter.
 */
class VisitorTrack implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if (! $request instanceof IncomingRequest || $request->getMethod() !== 'GET' || $request->isAJAX() || $response->getStatusCode() >= 400) {
            return;
        }

        try {
            helper('shop');
            $now  = date('Y-m-d H:i:s');
            $page = mb_substr('/' . ltrim(uri_string(), '/') . ($request->getUri()->getQuery() !== '' ? '?' . $request->getUri()->getQuery() : ''), 0, 255);
            $db   = db_connect();
            // LAST_INSERT_ID(id) makes insertID() return the existing row's id on a repeat visit
            $db->query(
                'INSERT INTO visitors (ip_address, visit_date, user_id, user_agent, last_page, hits, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, 1, ?, ?)
                 ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), hits = hits + 1, last_page = VALUES(last_page), user_agent = VALUES(user_agent),
                   user_id = COALESCE(VALUES(user_id), user_id), updated_at = VALUES(updated_at)',
                [
                    client_ip(),
                    date('Y-m-d'),
                    session('user_id') ?: null,
                    mb_substr((string) $request->getUserAgent(), 0, 255),
                    $page,
                    $now,
                    $now,
                ]
            );
            $db->table('visitor_page_views')->insert(['visitor_id' => $db->insertID(), 'page' => $page, 'viewed_at' => $now]);
        } catch (\Throwable $e) {
            // Never break the website because of visitor logging
            log_message('error', 'Visitor tracking failed: ' . $e->getMessage());
        }
    }
}
