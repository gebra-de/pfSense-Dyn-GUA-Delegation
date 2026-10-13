#!/usr/local/bin/php
<?php
/*
 * Copyright 2026 gebra
 * SPDX-License-Identifier: Apache-2.0
 *
 * Dynamic GUA-PD 0.8.0 Beta RC1 asynchronous native verification worker.
 * A dedicated process keeps package resync callbacks out of native DHCP locks.
 */
require_once('/usr/local/pkg/dgpd.inc');

$worker = lock('dgpd-worker', LOCK_EX | LOCK_NB);
if (!$worker) {
	exit(0);
}
$signal = fopen(DGPD_SIGNAL, 'c+');
if ($signal === false) {
	unlock($worker);
	error_log('DGPD worker: cannot open signal; persistent pending intent retained');
	exit(1);
}
$processed = null;
try {
	while (true) {
		if (!flock($signal, LOCK_EX)) {
			throw new RuntimeException('Cannot lock DGPD signal');
		}
		try {
			rewind($signal);
			$raw = trim(stream_get_contents($signal));
			if ($raw !== '' && !ctype_digit($raw)) {
				throw new RuntimeException('Invalid DGPD signal generation');
			}
			$requested = (int)$raw;
			if ($processed !== null && $processed === $requested) {
				/* Release ownership under the signal lock to avoid a lost wakeup. */
				unlock($worker);
				$worker = null;
				break;
			}
			$processed = $requested;
		} finally {
			flock($signal, LOCK_UN);
		}
		try {
			$result = dgpd_renumber();
			file_put_contents('/var/log/dgpd-reconcile.log',
				date('c') . " DGPD generation={$processed}: {$result}\n", FILE_APPEND | LOCK_EX);
		} catch (Throwable $error) {
			file_put_contents('/var/log/dgpd-reconcile.log', date('c') .
				" DGPD generation={$processed}: ERROR " . $error->getMessage() .
				"; pending intent retained, use Recover after resolving the condition\n", FILE_APPEND | LOCK_EX);
		}
	}
} catch (Throwable $error) {
	error_log('DGPD worker: ' . $error->getMessage());
	exit(1);
} finally {
	fclose($signal);
	unlock($worker);
}
